<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Paiement\PaiementController;
use Illuminate\Support\Facades\Log;

class PaymentRedirectController extends Controller
{
    /**
     * Retour succès URL
     */
    public function success(Request $request)
    {
        // CinetPay renvoie transaction_id dans l'URL lors du retour
        $transactionId = $request->input('transaction_id') ?? $request->input('ref');

        if (!$transactionId) {
            return view('paiement.erreur', ['message' => "Référence manquante."]);
        }

        $paiement = Paiement::where('reference', $transactionId)->first();

        if (!$paiement) {
            return view('paiement.erreur', ['message' => "Paiement introuvable."]);
        }

        // Si le paiement n'est pas encore marqué 'paye', on force une vérification API
        // Car le webhook peut avoir du retard
        if ($paiement->statut !== 'paye') {
            $statusData = PaiementController::verifyCinetPayStatus($transactionId, env('CINETPAY_SITE_ID'));
            
            if (($statusData['status'] ?? '') === 'ACCEPTED') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                ]);
                $paiement->messe->update(['statut' => 'en attente']);
            }
        }

        // Préparation du Deep Link pour retourner dans l'app mobile
        // status=success permet à ton app Flutter/React Native de savoir que c'est bon
        $redirectUrl = "maparoisse://paiement?status=success&reference={$transactionId}";

        return view('paiement.success', [
            'redirectUrl' => $redirectUrl,
            'paiement' => $paiement
        ]);
    }

    /**
     * Retour Annulation URL
     */
    public function cancel(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('ref');

        if ($transactionId) {
            $paiement = Paiement::where('reference', $transactionId)->first();
            if ($paiement) {
                $paiement->update(['statut' => 'annule']);
            }
        }

        $redirectUrl = "maparoisse://paiement?status=cancelled&reference={$transactionId}";

        return view('paiement.erreur', [
            'redirectUrl' => $redirectUrl,
            'message' => "Vous avez annulé le paiement."
        ]);
    }
}