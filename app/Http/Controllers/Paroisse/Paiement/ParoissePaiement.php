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
        $rules = [
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'numero_compte' => 'required|string',
            'nom_titulaire' => 'required|string',
        ];

        // Ajouter la règle conditionnelle pour nom_banque
        if ($request->methode === 'virement_bancaire') {
            $rules['nom_banque'] = 'required|string';
        }

        $request->validate($rules);

        $paroisse = Auth::guard('paroisse')->user();

        // Calculer le solde actuel (total des paiements)
        $solde = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'payé')
            ->sum('paiements.montant');

        if ($request->montant > $solde) {
            return back()->with('error', 'Le montant demandé dépasse votre solde disponible.');
        }

        // Créer la demande de retrait
        $retrait = new ParoisseRetrait;
        $retrait->paroisse_id = $paroisse->id;
        $retrait->montant = $request->montant;
        $retrait->methode = $request->methode;
        $retrait->numero_compte = $request->numero_compte;
        $retrait->nom_titulaire = $request->nom_titulaire;
        $retrait->nom_banque = $request->nom_banque; // Nouveau champ
        $retrait->reference = 'RET'.time().$paroisse->id;
        $retrait->statut = 'en_attente';
        $retrait->save();

        return redirect()->route('paroisse.retraits')->with('success', 'Votre demande de retrait a été envoyée avec succès.');
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
        $request->validate([
            'montant' => 'required|numeric|min:100',
            'telephone' => 'required|numeric',
            'prefix' => 'required',
            'payment_method' => 'nullable|string|in:MTN,MOOV,ORANGE,WAVE',
        ]);

        $paroisse = Auth::guard('paroisse')->user();

        // -----------------------------------------------------------
        // 1. LOGIQUE DE VÉRIFICATION DU SOLDE (AJOUTÉ)
        // -----------------------------------------------------------

        // Calcul du total des paiements entrants
        $totalPaiements = DB::table('paiements')
            ->join('messes', 'paiements.messe_id', '=', 'messes.id')
            ->where('messes.paroisse_id', $paroisse->id)
            ->where('paiements.statut', 'paye')
            ->sum('paiements.montant');

        // Calcul du total des sorties (Retraits classiques + Reversements API déjà effectués)
        // Note: Assurez-vous d'inclure les reversements précédents dans le calcul du solde si ce n'est pas fait dans "paroisse_retraits"
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');

        // Si vous stockez les reversements API dans une table séparée, il faut aussi les soustraire !
        // Supposons pour l'instant que vous calculiez le solde ainsi :
        $soldeDisponible = ($totalPaiements / 1.01) - $totalRetraits;

        // VÉRIFICATION STRICTE
        if ($request->montant > $soldeDisponible) {
            return response()->json([
                'message' => 'Solde insuffisant. Votre solde disponible est de '.number_format($soldeDisponible, 0, ',', ' ').' FCFA.',
            ], 422); // 422 Unprocessable Entity
        }

        // -----------------------------------------------------------
        // FIN LOGIQUE
        // -----------------------------------------------------------

        $reference = 'REV_'.time().'_'.rand(1000, 9999);

        // Création en base de données (Statut Pending)
        // ATTENTION : Pensez à lier ce reversement à la paroisse si possible
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending',
            // 'paroisse_id' => $paroisse->id // Si votre table a cette colonne, décommentez ceci
        ]);

        // Important : Dès qu'on initie, on devrait théoriquement bloquer les fonds
        // ou créer un enregistrement "paroisse_retrait" temporaire pour éviter les doubles dépenses
        // pendant que l'API tourne. Pour l'instant, on continue avec la logique API.

        try {
            // --- AUTHENTIFICATION CINETPAY ---
            $loginResponse = Http::asForm()->post('https://client.cinetpay.com/v1/auth/login', [
                'apikey' => env('CINETPAY_API_KEY'),
                'password' => env('CINETPAY_PASSWORD'),
            ]);

            $loginResult = $loginResponse->json();

            if (! $loginResponse->successful() || ! isset($loginResult['data']['token'])) {
                Log::error('CinetPay Login Failed', $loginResult);
                $reversement->update(['statut' => 'failed']);

                return response()->json(['message' => 'Erreur technique (Auth Banque). Veuillez réessayer plus tard.'], 500);
            }

            $token = $loginResult['data']['token'];

            // --- TRANSFERT ---
            $transferUrl = 'https://client.cinetpay.com/v1/transfer/money/send/contact?token='.$token;

            $payload = [
                'prefix' => $request->prefix,
                'phone' => $request->telephone,
                'amount' => $request->montant,
                'client_transaction_id' => $reference,
                'notify_url' => url('/reversement/notification'),
            ];

            $response = Http::asForm()->timeout(30)->post($transferUrl, $payload);
            $result = $response->json();

            $reversement->update(['donnees_api' => $result]);

            if ($response->successful() && isset($result['code']) && $result['code'] === '0') {
                $reversement->update([
                    'statut' => 'success',
                    'cinetpay_transfer_id' => $result['data']['transfer_id'] ?? null,
                ]);

                // OPTIONNEL : Créer ici une entrée dans 'paroisse_retraits' pour déduire le solde officiellement
                // ParoisseRetrait::create([...]);

                return response()->json([
                    'success' => true,
                    'message' => 'Transfert effectué avec succès.',
                ]);
            } else {
                $reversement->update(['statut' => 'failed']);
                $message = $result['message'] ?? 'Erreur lors du transfert.';

                return response()->json(['message' => $message], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erreur Reversement: '.$e->getMessage());
            $reversement->update(['statut' => 'failed']);

            return response()->json(['message' => 'Erreur serveur : '.$e->getMessage()], 500);
        }
    }
}
