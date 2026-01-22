<?php

namespace App\Http\Controllers\Paroisse\Paiement;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use App\Models\Reversement;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ParoissePaiement extends Controller
{
    protected $cinetpay;

    public function __construct(CinetPayService $cinetpay)
    {
        $this->cinetpay = $cinetpay;
    }

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
     * Méthode pour le Mobile Money (CinetPay)
     */
    public function store(Request $request)
    {
        Log::info('Début du processus de reversement (Mobile Money).', [
            'paroisse_id' => Auth::guard('paroisse')->id(),
            'montant' => $request->montant,
            'methode' => $request->methode,
            'telephone' => $request->telephone,
        ]);

        // 1. Validation
        try {
            $request->validate([
                'montant' => 'required|numeric|min:1000',
                'telephone' => 'required|numeric',
                'prefix' => 'required',
                'methode' => 'required|string',
            ]);
            Log::info('Validation des données réussie.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Échec de la validation pour le reversement.', ['errors' => $e->errors()]);
            throw $e;
        }

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            Log::warning('Tentative de retrait avec solde insuffisant.', [
                'paroisse' => $paroisse->id,
                'demandé' => $request->montant,
                'disponible' => $soldeDisponible,
            ]);

            return response()->json([
                'message' => 'Solde insuffisant. Disponible : '.number_format($soldeDisponible, 0, ',', ' ').' FCFA.',
            ], 422);
        }

        // 2. Référence unique
        $reference = 'REV_'.time().'_'.rand(1000, 9999);
        Log::info('Génération de la référence unique: '.$reference);

        // 3. Création du reversement (Log)
        $reversement = Reversement::create([
            'reference' => $reference,
            'numero_destinataire' => $request->telephone,
            'prefix_pays' => $request->prefix,
            'montant' => $request->montant,
            'statut' => 'pending',
            'paroisse_id' => $paroisse->id,
        ]);
        Log::info('Enregistrement du reversement en base de données.', ['id' => $reversement->id]);

        try {
            // 4. Appel au service CinetPay
            Log::info('Appel à l\'API CinetPay (sendMoney)...');
            $result = $this->cinetpay->sendMoney(
                $request->prefix,
                $request->telephone,
                $request->montant,
                $reference,
                $paroisse->name
            );

            Log::info('Réponse traitée de CinetPay Service:', ['result' => $result]);
            $reversement->update(['donnees_api' => $result]);

            // 5. Gestion Réussite / Échec
            if (isset($result['code']) && ($result['code'] == '0' || $result['code'] == '201' || $result['code'] == '00')) {
                Log::info('Le transfert CinetPay a été accepté par l\'API.', ['data' => $result['data'] ?? null]);

                // SUCCÈS - L'argent est envoyé ou en cours chez CinetPay
                $reversement->update([
                    'statut' => 'success',
                    'cinetpay_transfer_id' => $result['data']['transfer_id'] ?? null,
                ]);

                Log::info('Création de la fiche de retrait correspondante (ParoisseRetrait).');
                $retrait = new ParoisseRetrait;
                $retrait->paroisse_id = $paroisse->id;
                $retrait->montant = $request->montant;
                $retrait->methode = $request->methode;
                $retrait->numero_compte = '(+'.$request->prefix.') '.$request->telephone;
                $retrait->nom_titulaire = $paroisse->name.' (Mobile Money)';
                $retrait->reference = $reference;
                $retrait->statut = 'initié'; // Statut initié pour mobile money
                $retrait->traite_le = now();
                $retrait->save();

                Log::info('Processus de reversement terminé avec succès.', ['retrait_id' => $retrait->id]);

                return response()->json([
                    'success' => true,
                    'message' => 'Le retrait via '.ucfirst($request->methode).' a été initié avec succès.',
                ]);
            } else {
                // ÉCHEC
                Log::error('Le transfert CinetPay a été rejeté par l\'API.', ['result' => $result]);
                $reversement->update(['statut' => 'failed']);
                $errorMessage = $result['message'] ?? $result['msg'] ?? 'Erreur inconnue';

                return response()->json([
                    'message' => "Le transfert a échoué : $errorMessage",
                ], 400);
            }

        } catch (\Exception $e) {
            Log::critical('Exception fatale lors du reversement: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

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
        Log::info('Début du processus de demande de virement bancaire.', [
            'paroisse_id' => Auth::guard('paroisse')->id(),
            'montant' => $request->montant,
            'banque' => $request->nom_banque,
        ]);

        // Validation Stricte
        try {
            $request->validate([
                'montant' => 'required|numeric|min:1000',
                'methode' => 'required|string',
                'numero_compte' => 'required|string',
                'nom_titulaire' => 'required|string',
                'nom_banque' => 'required|string',
            ]);
            Log::info('Validation des données de virement réussie.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Échec de la validation pour le virement.', ['errors' => $e->errors()]);
            throw $e;
        }

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            Log::warning('Tentative de virement avec solde insuffisant.', [
                'paroisse' => $paroisse->id,
                'demandé' => $request->montant,
                'disponible' => $soldeDisponible,
            ]);

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
            $retrait->nom_titulaire = $request->nom_titulaire;
            $retrait->nom_banque = $request->nom_banque;
            $retrait->reference = 'RET'.time().$paroisse->id;
            $retrait->statut = 'en_attente';
            $retrait->save();

            Log::info('Demande de virement bancaire enregistrée avec succès.', ['retrait_id' => $retrait->id]);

            return response()->json([
                'success' => true,
                'message' => 'Votre demande de virement a été envoyée avec succès.',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la demande de virement: '.$e->getMessage());

            return response()->json([
                'message' => 'Une erreur interne est survenue.',
            ], 500);
        }
    }

    /**
     * Gère la notification CinetPay (Callback)
     */
    public function handleNotification(Request $request)
    {
        Log::info('Réception d\'une notification CinetPay (Callback).', [
            'raw_data' => $request->all(),
        ]);

        $client_transaction_id = $request->input('client_transaction_id');
        $status_cinetpay = $request->input('status');

        Log::info('Traitement de la notification pour la référence : '.$client_transaction_id);

        $retrait = ParoisseRetrait::where('reference', $client_transaction_id)->first();
        $reversement = Reversement::where('reference', $client_transaction_id)->first();

        if ($retrait) {
            Log::info('Enregistrement ParoisseRetrait trouvé.', ['id' => $retrait->id, 'ancien_statut' => $retrait->statut]);
            if ($status_cinetpay == 1 || $status_cinetpay == 'ACCEPTED') {
                $retrait->statut = 'traite';
                Log::info('Mise à jour statut ParoisseRetrait : traite.');
            } else {
                $retrait->statut = 'rejete';
                $retrait->raison_rejet = 'Echec reporté par CinetPay';
                Log::warning('Mise à jour statut ParoisseRetrait : rejete.');
            }
            $retrait->save();
        } else {
            Log::warning('Aucun ParoisseRetrait correspondant à la référence '.$client_transaction_id);
        }

        if ($reversement) {
            Log::info('Enregistrement Reversement trouvé.', ['id' => $reversement->id, 'ancien_statut' => $reversement->statut]);
            
            // On considère '1', 'ACCEPTED' ou '00' comme un succès pour les reversements
            $nouveauStatut = ($status_cinetpay == 1 || $status_cinetpay == 'ACCEPTED' || $status_cinetpay == '00' || $status_cinetpay == 'SUCCES') ? 'success' : 'failed';
            
            $reversement->update([
                'statut' => $nouveauStatut,
                'donnees_api' => array_merge($reversement->donnees_api ?? [], [
                    'last_notification' => $request->all(),
                    'notified_at' => now()->toDateTimeString()
                ])
            ]);
            Log::info('Mise à jour statut Reversement : ' . $nouveauStatut);
        } else {
            Log::warning('Aucun Reversement correspondant à la référence ' . $client_transaction_id);
        }

        return response()->json(['code' => 200, 'message' => 'Notification traitée avec succès']);
    }
}
