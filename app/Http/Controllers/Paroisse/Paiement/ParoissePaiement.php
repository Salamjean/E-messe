<?php

namespace App\Http\Controllers\Paroisse\Paiement;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use App\Models\Reversement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ParoissePaiement extends Controller
{
    public function history()
    {
        $paroisse = Auth::guard('paroisse')->user();

        // Récupérer les retraits de la paroisse
        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');

        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits;

        return view('paroisse.retrait.history', compact('retraits', 'soldeDisponible'));
    }

    public function create()
    {
        $paroisse = Auth::guard('paroisse')->user();
        // Calculer le montant total des paiements pour cette paroisse
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        // Calculer le total des retraits déjà effectués
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete') // seulement les retraits complétés
            ->sum('montant');

        // Calculer le solde disponible (paiements - retraits)
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits;

        return view('paroisse.retrait.create', compact('soldeDisponible'));
    }

    public function requestRetrait(Request $request)
    {
        // Déterminer si c'est un retrait mobile money
        $mobileMoneyMethods = ['wave', 'orange_money', 'mtn_money', 'moov_money'];
        $isMobileMoney = in_array($request->methode, $mobileMoneyMethods);

        // 1️⃣ Validation conditionnelle
        if ($isMobileMoney) {
            $rules = [
                'montant' => 'required|numeric|min:1000',
                'methode' => 'required|string',
                'telephone' => 'required|numeric',
                'prefix' => 'required',
                'nom_titulaire' => 'required|string',
            ];
        } else {
            // Virement bancaire
            $rules = [
                'montant' => 'required|numeric|min:1000',
                'methode' => 'required|string',
                'numero_compte' => 'required|string',
                'nom_titulaire' => 'required|string',
                'nom_banque' => 'required|string',
            ];
        }

        $request->validate($rules);

        $paroisse = Auth::guard('paroisse')->user();

        // 2️⃣ Calcul du solde disponible
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        // Inclure les reversements dans le calcul du solde
        $totalReversementsApi = Reversement::where('paroisse_id', $paroisse->id)
            ->whereIn('statut', ['success', 'pending'])
            ->sum('montant');

        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits - $totalReversementsApi;

        if ($request->montant > $soldeDisponible) {
            return back()->with('error', 'Le montant demandé dépasse votre solde disponible ('.number_format($soldeDisponible, 0, ',', ' ').' FCFA).');
        }

        // 3️⃣ Traitement différencié selon la méthode
        if ($isMobileMoney) {
            // MOBILE MONEY - Reversement automatique via CinetPay
            return $this->processMobileMoneyWithdrawal($request, $paroisse, $soldeDisponible);
        } else {
            // VIREMENT BANCAIRE - Workflow manuel
            return $this->processBankTransferWithdrawal($request, $paroisse);
        }
    }

    /**
     * Traiter un retrait mobile money avec reversement automatique via CinetPay
     */
    private function processMobileMoneyWithdrawal(Request $request, $paroisse, $soldeDisponible)
    {
        // Génération référence unique
        $reference = 'REV_'.time().'_'.rand(1000, 9999);

        // Créer d'abord l'enregistrement dans la table reversements
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending',
            'paroisse_id' => $paroisse->id,
        ]);

        try {
            // Authentification CinetPay
            $apiKey = env('CINETPAY_API_KEY');
            $password = env('CINETPAY_PASSWORD');

            Log::info("Tentative de connexion CinetPay pour le transfert $reference");

            $loginResponse = Http::asForm()->post('https://client.cinetpay.com/v1/auth/login', [
                'apikey' => $apiKey,
                'password' => $password,
            ]);

            $loginResult = $loginResponse->json();

            if (!$loginResponse->successful() || !isset($loginResult['data']['token'])) {
                Log::error('CinetPay Login Error', ['response' => $loginResult]);
                $reversement->update(['statut' => 'failed']);

                $msg = $loginResult['message'] ?? 'Erreur inconnue';
                $desc = $loginResult['description'] ?? '';

                return back()->with('error', "Échec authentification bancaire: $msg ($desc). Veuillez réessayer plus tard.");
            }

            $token = $loginResult['data']['token'];
            $transferUrl = 'https://client.cinetpay.com/v1/transfer/money/send/contact?token='.$token;

            // Préparation payload reversement
            $payload = [
                'prefix' => $request->prefix,
                'phone' => $request->telephone,
                'amount' => $request->montant,
                'client_transaction_id' => $reference,
                'notify_url' => url('/api/reversement/notification'),
            ];

            // Ajouter le payment_method si fourni
            if ($request->has('payment_method') && !empty($request->payment_method)) {
                $payload['payment_method'] = $request->payment_method;
            }

            Log::info('Envoi demande transfert', $payload);

            $response = Http::asForm()->timeout(60)->post($transferUrl, $payload);
            $result = $response->json();

            $reversement->update(['donnees_api' => json_encode($result)]);

            // Vérification succès CinetPay
            if ($response->successful() && isset($result['code']) && $result['code'] === '0') {

                $transferId = $result['data']['transfer_id'] ?? null;

                $reversement->update([
                    'statut' => 'success',
                    'cinetpay_transfer_id' => $transferId,
                ]);

                // Créer l'enregistrement de retrait paroisse avec statut "traité"
                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = $request->methode;
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;
                $retrait->nom_titulaire = $request->nom_titulaire;
                $retrait->reference = $reference;
                $retrait->statut = 'traite';
                $retrait->traite_le = now();
                $retrait->save();

                return redirect()->route('paroisse.retraits')->with('success', '✅ Votre retrait a été traité avec succès ! Le transfert de '.number_format($request->montant, 0, ',', ' ').' FCFA a été effectué.');

            } else {
                $reversement->update(['statut' => 'failed']);

                $errorMessage = $result['message'] ?? 'Erreur lors du traitement CinetPay';
                $description = $result['description'] ?? '';

                Log::error("Echec Transfert CinetPay: $errorMessage - $description");

                // Créer un enregistrement de retrait avec statut "rejeté"
                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = $request->methode;
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;
                $retrait->nom_titulaire = $request->nom_titulaire;
                $retrait->reference = $reference;
                $retrait->statut = 'rejete';
                $retrait->raison_rejet = "Échec CinetPay: $errorMessage - $description";
                $retrait->traite_le = now();
                $retrait->save();

                return back()->with('error', "❌ Le transfert a échoué : $errorMessage. $description");
            }

        } catch (\Exception $e) {
            Log::critical('Exception Reversement: '.$e->getMessage());
            $reversement->update(['statut' => 'failed']);

            // Créer un enregistrement de retrait avec statut "rejeté"
            $retrait = new ParoisseRetrait;
            $retrait->paroisse_id = $paroisse->id;
            $retrait->montant = $request->montant;
            $retrait->methode = $request->methode;
            $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;
            $retrait->nom_titulaire = $request->nom_titulaire;
            $retrait->reference = $reference;
            $retrait->statut = 'rejete';
            $retrait->raison_rejet = 'Erreur serveur critique: '.$e->getMessage();
            $retrait->traite_le = now();
            $retrait->save();

            return back()->with('error', 'Erreur serveur critique. Veuillez contacter le support.');
        }
    }

    /**
     * Traiter un retrait par virement bancaire (workflow manuel)
     */
    private function processBankTransferWithdrawal(Request $request, $paroisse)
    {
        // Créer la demande de retrait avec statut "en_attente"
        $retrait = new ParoisseRetrait;
        $retrait->paroisse_id = $paroisse->id;
        $retrait->montant = $request->montant;
        $retrait->methode = $request->methode;
        $retrait->numero_compte = $request->numero_compte;
        $retrait->nom_titulaire = $request->nom_titulaire;
        $retrait->nom_banque = $request->nom_banque;
        $retrait->reference = 'RET'.time().$paroisse->id;
        $retrait->statut = 'en_attente';
        $retrait->save();

        return redirect()->route('paroisse.retraits')->with('success', 'Votre demande de retrait a été envoyée avec succès. Elle sera traitée sous 2 à 3 jours ouvrés.');
    }

    public function annuler($id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);

        // Vérifier que le retrait appartient à la paroisse connectée
        if ($retrait->paroisse_id !== Auth::guard('paroisse')->id()) {
            return back()->with('error', 'Action non autorisée.');
        }

        // Vérifier que le retrait est encore en attente
        if ($retrait->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les retraits en attente peuvent être annulés.');
        }

        // Annuler le retrait
        $retrait->statut = 'rejete';
        $retrait->save();

        return back()->with('success', 'La demande de retrait a été annulée avec succès.');
    }

    public function index()
    {
        $paroisse = Auth::guard('paroisse')->user();

        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits;

        return view('paroisse.retrait.index', compact('retraits', 'soldeDisponible'));
    }

    // ... (Je garde les autres méthodes identiques pour la clarté, passons à getData et store) ...

    public function list_reversement()
    {
        $paroisse = Auth::guard('paroisse')->user();

        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits;

        return view('paroisse.reversement.index', compact('soldeDisponible'));
    }

    public function getData(Request $request)
    {
        try {
            // Note: Idéalement, filtrez aussi par paroisse_id si la table reversements a cette colonne
            $query = Reversement::query()->select(['reference', 'created_at', 'numero_destinataire', 'prefix_pays', 'montant', 'statut']);

            return DataTables::of($query)
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->editColumn('montant', function ($row) {
                    return number_format($row->montant, 0, ',', ' ').' FCFA';
                })
                ->editColumn('statut', function ($row) {
                    if ($row->statut == 'success') {
                        return '<span class="badge bg-success">Succès</span>';
                    }
                    if ($row->statut == 'failed') {
                        return '<span class="badge bg-danger">Échec</span>';
                    }

                    return '<span class="badge bg-warning text-dark">En attente</span>';
                })
                ->rawColumns(['statut'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Erreur DataTables: '.$e->getMessage());

            return response()->json(['error' => 'Erreur lors du chargement des données'], 500);
        }
    }

    public function store(Request $request)
    {
        // 1️⃣ Validation des champs
        $request->validate([
            'montant' => 'required|numeric|min:100',
            'telephone' => 'required|numeric',
            'prefix' => 'required',
        ]);

        $paroisse = Auth::guard('paroisse')->user();

        // 2️⃣ Calcul du solde disponible
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        $totalReversementsApi = Reversement::where('paroisse_id', $paroisse->id)
            ->whereIn('statut', ['success', 'pending'])
            ->sum('montant');

        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits - $totalReversementsApi;

        if ($request->montant > $soldeDisponible) {
            return response()->json([
                'message' => 'Solde insuffisant. Disponible : '.number_format($soldeDisponible, 0, ',', ' ').' FCFA.',
            ], 422);
        }

        // 3️⃣ Génération référence unique
        $reference = 'REV_'.time().'_'.rand(1000, 9999);

        // 4️⃣ Création du reversement en BDD
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending',
            'paroisse_id' => $paroisse->id,
        ]);

        try {
            // 5️⃣ Authentification CinetPay
            $apiKey = env('CINETPAY_API_KEY');
            $password = env('CINETPAY_PASSWORD');

            Log::info("Tentative de connexion CinetPay pour le transfert $reference");

            $loginResponse = Http::asForm()->post('https://client.cinetpay.com/v1/auth/login', [
                'apikey' => $apiKey,
                'password' => $password,
            ]);

            $loginResult = $loginResponse->json();

            if (! $loginResponse->successful() || ! isset($loginResult['data']['token'])) {
                Log::error('CinetPay Login Error', ['response' => $loginResult]);
                $reversement->update(['statut' => 'failed']);

                $msg = $loginResult['message'] ?? 'Erreur inconnue';
                $desc = $loginResult['description'] ?? '';

                return response()->json([
                    'message' => "Échec authentification bancaire: $msg ($desc). Vérifiez vos identifiants dans le .env",
                ], 500);
            }

            $token = $loginResult['data']['token'];
            $transferUrl = 'https://client.cinetpay.com/v1/transfer/money/send/contact?token='.$token;

            // 6️⃣ Préparation payload reversement
            $payload = [
                'prefix' => $request->prefix,
                'phone' => $request->telephone,
                'amount' => $request->montant,
                'client_transaction_id' => $reference,
                'notify_url' => url('/api/reversement/notification'),
            ];

            Log::info('Envoi demande transfert', $payload);

            $response = Http::asForm()->timeout(60)->post($transferUrl, $payload);
            $result = $response->json();

            $reversement->update(['donnees_api' => json_encode($result)]);

            // 7️⃣ Vérification succès CinetPay
            if ($response->successful() && isset($result['code']) && $result['code'] === '0') {

                $transferId = $result['data']['transfer_id'] ?? null;

                $reversement->update([
                    'statut' => 'success',
                    'cinetpay_transfer_id' => $transferId,
                ]);

                // 8️⃣ Enregistrement retrait paroisse
                DB::table('paroisse_retraits')->insert([
                    'paroisse_id' => $paroisse->id,
                    'montant' => $request->montant,
                    'statut' => 'valide',
                    'motif' => 'Reversement mobile money ref: '.$reference,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transfert initié avec succès.',
                    'data' => $result['data'],
                ]);

            } else {
                $reversement->update(['statut' => 'failed']);

                $errorMessage = $result['message'] ?? 'Erreur lors du traitement CinetPay';
                $description = $result['description'] ?? '';

                Log::error("Echec Transfert CinetPay: $errorMessage - $description");

                return response()->json([
                    'message' => "Le transfert a échoué : $errorMessage. $description",
                ], 400);
            }

        } catch (\Exception $e) {
            Log::critical('Exception Reversement: '.$e->getMessage());
            $reversement->update(['statut' => 'failed']);

            return response()->json([
                'message' => 'Erreur serveur critique. Veuillez contacter le support.',
            ], 500);
        }
    }
}
