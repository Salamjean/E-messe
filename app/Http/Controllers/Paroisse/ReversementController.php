<?php

namespace App\Http\Controllers\Paroisse;

use App\Http\Controllers\Controller;
use App\Models\ParoisseRetrait;
use App\Models\Reversement;
use App\Services\CinetPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReversementController extends Controller
{
    protected $cinetpay;

    public function __construct(CinetPayService $cinetpay)
    {
        $this->cinetpay = $cinetpay;
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

    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'montant' => 'required|numeric|min:1000',
            'methode' => 'required|string',
            'prefix' => 'required|string',
            'telephone' => 'required|string',
        ]);

        $paroisse = Auth::guard('paroisse')->user();
        $soldeDisponible = $this->calculerSolde($paroisse->id);

        if ($request->montant > $soldeDisponible) {
            return response()->json(['message' => 'Solde insuffisant.'], 422);
        }

        DB::beginTransaction();

        try {
            // Nettoyage du numéro (supprimer espaces et tirets)
            $cleanPhone = preg_replace('/[^0-9]/', '', $request->telephone);
            $cleanPrefix = preg_replace('/[^0-9]/', '', $request->prefix);

            $reference = 'REV-'.time().'-'.$paroisse->id;

            $retrait = new ParoisseRetrait;
            $retrait->paroisse_id = $paroisse->id;
            $retrait->montant = $request->montant;
            $retrait->methode = $request->methode;
            $retrait->numero_compte = $cleanPrefix.$cleanPhone;
            $retrait->nom_banque = $request->methode;
            $retrait->nom_titulaire = $paroisse->name;
            $retrait->reference = $reference;
            $retrait->statut = 'traitement_cours';
            $retrait->save();

            // Appel Service
            $result = $this->cinetpay->sendMoney(
                $cleanPrefix,
                $cleanPhone,
                $request->montant,
                $reference,
                $paroisse->name
            );

            // Vérification du succès
            // CinetPay renvoie code:0 pour succès, ou parfois code:200 selon endpoint
            $isSuccess = false;

            if (isset($result['code'])) {
                if ($result['code'] === 0 || $result['code'] === '0' || $result['code'] === 'SUCCESS') {
                    $isSuccess = true;
                }
            }

            if ($isSuccess) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Transfert initié. Statut opérateur : '.($result['message'] ?? 'En traitement'),
                ]);
            } else {
                // Echec
                DB::rollBack();
                $msg = $result['message'] ?? ($result['msg'] ?? 'Erreur inconnue');

                // Si c'est l'erreur 401 qui remonte via le tableau
                if (isset($result['code']) && $result['code'] === 'AUTH_ERROR') {
                    $msg = 'Erreur de configuration (Authentification échouée).';
                }

                return response()->json(['message' => 'Echec : '.$msg], 422);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur Controller: '.$e->getMessage());

            return response()->json(['message' => 'Erreur interne.'], 500);
        }
    }

    public function notifyCinetPay(Request $request)
    {
        // CinetPay envoie des données POST (client_transaction_id, status, etc.)
        // Loggez la requête pour déboguer au début
        Log::info('CinetPay Callback (ReversementController):', [
            'data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $client_transaction_id = $request->input('client_transaction_id');
        // Statut possible : 1 (Succès), 2 (Echec), 00 (Succès)
        $status_cinetpay = $request->input('status');
        Log::info('Traitement notification (ReversementController) pour REF: '.$client_transaction_id.' Statut: '.$status_cinetpay);
        // Retrouver la transaction
        $retrait = ParoisseRetrait::where('reference', $client_transaction_id)->first();

        if ($retrait) {
            if ($status_cinetpay == 1 || $status_cinetpay == 'ACCEPTED') { // Vérifiez le code exact de succès
                $retrait->statut = 'valide';
                // Si le solde n'a pas été déduit à l'initiation, faites-le ici
            } else {
                $retrait->statut = 'echoue';
                // Si le solde a été déduit, remboursez-le ici
            }
            $retrait->save();
        }

        return response()->json(['code' => 200, 'message' => 'OK']);
    }
}
