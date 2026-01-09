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

        $retraits = ParoisseRetrait::where('paroisse_id', $paroisse->id)
            ->where('statut', '!=', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $soldeDisponible = $this->calculerSolde($paroisse->id);

        return view('paroisse.retrait.history', compact('retraits', 'soldeDisponible'));
    }

    public function create()
    {
        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        return view('paroisse.retrait.create', compact('soldeDisponible'));
    }

    /**
     * Méthode utilitaire pour calculer le solde
     */

    /**
     * Traite la demande de Virement Bancaire (Route : paroisse.retrait.request)
     */

    /**
     * Traite la demande Mobile Money via CinetPay (Route : reversement.store)
     */
    public function annuler($id)
    {
        $retrait = ParoisseRetrait::findOrFail($id);

        if ($retrait->paroisse_id !== Auth::guard('paroisse')->id()) {
            return back()->with('error', 'Action non autorisée.');
        }

        if ($retrait->statut !== 'en_attente') {
            return back()->with('error', 'Seuls les retraits en attente peuvent être annulés.');
        }

        $retrait->statut = 'rejete';
        $retrait->raison_rejet = 'Annulé par l\'utilisateur';
        $retrait->traite_le = now();
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

        $soldeDisponible = $this->calculerSolde($paroisse->id);

        return view('paroisse.retrait.index', compact('retraits', 'soldeDisponible'));
    }

    public function list_reversement()
    {
        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        return view('paroisse.reversement.index', compact('soldeDisponible'));
    }

    public function getData(Request $request)
    {
        try {
            $paroisseId = Auth::guard('paroisse')->id();

            $query = Reversement::query()
                ->where('paroisse_id', $paroisseId) // Filtrage par paroisse ajouté pour sécurité
                ->select(['reference', 'created_at', 'numero_destinataire', 'prefix_pays', 'montant', 'statut']);

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

    // AJOUTEZ OU REMPLACEZ CETTE MÉTHODE UTILITAIRE SI ELLE N'EXISTE PAS
    private function calculerSolde($paroisseId)
    {
        // Somme des offrandes pour les paiements reçus (statut 'paye')
        $totalRecettesOffrande = DB::table('messes')
            ->join('paiements', 'messes.id', '=', 'paiements.messe_id')
            ->where('messes.paroisse_id', $paroisseId)
            ->where('paiements.statut', 'paye')
            ->sum('messes.montant_offrande');

        // Somme des retraits effectués (hors rejetés) via paroisse_retraits
        // Note: Les reversements API réussis créent aussi une entrée dans paroisse_retraits avec le statut 'traite'.
        $totalRetraits = DB::table('paroisse_retraits')
            ->where('paroisse_id', $paroisseId)
            ->where('statut', '!=', 'rejete')
            ->sum('montant');



        // Somme des reversements via API qui sont encore en attente (non encore dans paroisse_retraits)
        $totalReversementsApiPending = Reversement::where('paroisse_id', $paroisseId)
            ->where('statut', 'pending')
            ->sum('montant');

        // Calcul du solde disponible (Formule : Recettes Offrandes - Retraits - Reversements en attente)
        return (int) $totalRecettesOffrande - (int) ($totalRetraits + $totalReversementsApiPending);
    }

    /**
     * Méthode CORRIGÉE pour le Mobile Money (CinetPay)
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'telephone' => 'required|numeric',
            'prefix' => 'required',
        ]);

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            return response()->json([
                'message' => 'Solde insuffisant. Disponible : '.number_format($soldeDisponible, 0, ',', ' ').' FCFA.',
            ], 422);
        }

        // 2. Référence unique
        $reference = 'REV_'.time().'_'.rand(1000, 9999);

        // 3. Création du reversement (Log)
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending',
            'paroisse_id' => $paroisse->id,
        ]);

        try {
            // 4. Authentification CinetPay
            $apiKey = env('CINETPAY_API_KEY');
            $password = env('CINETPAY_PASSWORD');

            if (empty($apiKey) || empty($password)) {
                throw new \Exception('Clés API CinetPay non configurées.');
            }

            $loginResponse = Http::asForm()->post('https://client.cinetpay.com/v1/auth/login', [
                'apikey' => $apiKey,
                'password' => $password,
            ]);

            $loginResult = $loginResponse->json();

            if (! $loginResponse->successful() || ! isset($loginResult['data']['token'])) {
                $reversement->update(['statut' => 'failed']);
                Log::error('CinetPay Login Error', ['response' => $loginResult]);

                return response()->json([
                    'message' => 'Erreur de connexion bancaire. Veuillez réessayer plus tard.',
                ], 500);
            }

            $token = $loginResult['data']['token'];
            $transferUrl = 'https://client.cinetpay.com/v1/transfer/money/send/contact?token='.$token;

            // 5. Envoi Transfert
            $payload = [
                'prefix' => $request->prefix,
                'phone' => $request->telephone,
                'amount' => $request->montant,
                'client_transaction_id' => $reference,
                'notify_url' => url('/api/reversement/notification'),
            ];

            Log::info("Envoi CinetPay ($reference)", $payload);

            $response = Http::asForm()->timeout(60)->post($transferUrl, $payload);
            $result = $response->json();

            $reversement->update(['donnees_api' => json_encode($result)]);

            // 6. Gestion Réussite / Échec
            if ($response->successful() && isset($result['code']) && $result['code'] === '0') {
                // SUCCÈS
                $transferId = $result['data']['transfer_id'] ?? null;

                $reversement->update([
                    'statut' => 'success',
                    'cinetpay_transfer_id' => $transferId,
                ]);

                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = 'mobile_money'; // Ou $request->methode si disponible
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;

                // --- CORRECTION CRITIQUE 1 : Ajout d'une valeur par défaut pour éviter l'erreur SQL 1364 ---
                $retrait->nom_titulaire = $paroisse->name.' (Mobile Money)';

                $retrait->reference = $reference;
                $retrait->statut = 'traite';
                $retrait->traite_le = now();
                $retrait->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Transfert initié avec succès.',
                    'data' => $result['data'],
                ]);

            } else {
                // ÉCHEC (CinetPay a répondu mais avec une erreur)
                $reversement->update(['statut' => 'failed']);

                $errorMessage = $result['message'] ?? 'Erreur inconnue';
                $desc = $result['description'] ?? '';

                Log::error("Echec Transfert CinetPay: $errorMessage - $desc");

                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = 'mobile_money';
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;

                // --- CORRECTION CRITIQUE 2 : Ajout de la valeur par défaut ici aussi ---
                $retrait->nom_titulaire = 'Utilisateur Mobile Money';

                $retrait->reference = $reference;
                $retrait->statut = 'rejete';
                $retrait->raison_rejet = "API Error: $errorMessage";
                $retrait->traite_le = now();
                $retrait->save();

                return response()->json([
                    'message' => "Le transfert a échoué : $errorMessage. $desc",
                ], 400);
            }

        } catch (\Exception $e) {
            // ÉCHEC CRITIQUE (Exception PHP/Serveur)
            Log::critical('Exception Reversement: '.$e->getMessage());

            if (isset($reversement)) {
                $reversement->update(['statut' => 'failed']);
            }

            // On essaie d'enregistrer l'échec dans paroisse_retraits si possible
            try {
                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = 'mobile_money';
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;

                // --- CORRECTION CRITIQUE 3 : Ajout de la valeur par défaut ---
                $retrait->nom_titulaire = 'Erreur Système';

                $retrait->reference = $reference ?? 'ERR_'.time();
                $retrait->statut = 'rejete';
                $retrait->raison_rejet = 'Exception: '.substr($e->getMessage(), 0, 100);
                $retrait->traite_le = now();
                $retrait->save();
            } catch (\Exception $ex) {
                // Si même ça échoue, on log juste
                Log::error('Impossible de sauvegarder le retrait rejeté: '.$ex->getMessage());
            }

            return response()->json([
                'message' => 'Erreur serveur lors du traitement. Contactez le support.',
            ], 500);
        }
    }

    /**
     * Méthode pour le Virement Bancaire (Reste quasi identique mais avec retour JSON)
     */
    public function requestRetrait(Request $request)
    {
        // Validation Stricte
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'numero_compte' => 'required|string',
            'nom_titulaire' => 'required|string',
            'nom_banque' => 'required|string',
        ]);

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            return response()->json([
                'message' => 'Solde insuffisant. Disponible : '.number_format($soldeDisponible, 0, ',', ' ').' FCFA.',
            ], 422);
        }

        try {
            $retrait = new ParoisseRetrait;
            $retrait->paroisse_id = $paroisse->id;
            $retrait->montant = $request->montant;
            $retrait->methode = 'virement_bancaire';
            $retrait->numero_compte = $request->numero_compte;
            $retrait->nom_titulaire = $request->nom_titulaire; // Valeur du formulaire
            $retrait->nom_banque = $request->nom_banque;
            $retrait->reference = 'RET'.time().$paroisse->id;
            $retrait->statut = 'en_attente';
            $retrait->save();

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de virement a été envoyée avec succès.',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur demande retrait: '.$e->getMessage());

            return response()->json([
                'message' => 'Une erreur interne est survenue.',
            ], 500);
        }
    }
}
