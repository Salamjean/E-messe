<?php

namespace App\Http\Controllers\Redirectionpaiement;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // N'oublie pas d'importer HTTP

class PaymentRedirectController extends Controller
{
    /**
     * Retour succès URL
     * Gère le POST renvoyé par CinetPay et met à jour le statut
     */
    public function success(Request $request)
    {
        // 1. Récupération de l'ID de transaction (envoyé en POST ou GET)
        // CinetPay V2 utilise souvent 'transaction_id' dans le body du POST
        $transactionId = $request->input('transaction_id')
                         ?? $request->input('cpm_trans_id')
                         ?? $request->input('ref');

        if (! $transactionId) {
            return view('paiement.erreur', ['message' => 'Référence de transaction manquante.']);
        }

        // 2. Recherche du paiement en base
        $paiement = Paiement::with('messe')->where('reference', $transactionId)->first();

        if (! $paiement) {
            return view('paiement.erreur', ['message' => 'Paiement introuvable.']);
        }

        // 3. Vérification et Mise à jour
        // Si le paiement n'est pas encore marqué 'paye', on vérifie auprès de l'API CinetPay
        if ($paiement->statut !== 'paye') {

            try {
                // Appel direct à l'API de vérification CinetPay pour sécuriser la transaction
                $response = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment/check', [
                    'apikey' => env('CINETPAY_API_KEY'),
                    'site_id' => env('CINETPAY_SITE_ID'),
                    'transaction_id' => $transactionId,
                ]);

                $result = $response->json();

                // Le code '00' signifie Succès chez CinetPay
                if (isset($result['code']) && $result['code'] === '00') {

                    // A. Mise à jour du Paiement
                    $paiement->update([
                        'statut' => 'paye',
                        'date_paiement' => now(),
                        'donnees_transaction' => $result['data'] ?? [], // On sauvegarde la réponse API
                    ]);

                    // B. Mise à jour de la Messe liée
                    if ($paiement->messe) {
                        $paiement->messe->update(['statut' => 'en attente']);
                    }

                    Log::info('Paiement CinetPay VALIDÉ pour la référence : '.$transactionId);

                } else {
                    Log::warning('Paiement CinetPay échoué ou en attente lors du retour : ', $result);
                }

            } catch (\Exception $e) {
                Log::error('Erreur lors de la vérification CinetPay : '.$e->getMessage());
                // On ne bloque pas l'utilisateur, mais le statut restera 'en_attente' jusqu'au webhook
            }
        }

        // 4. Redirection vers l'application mobile (Deep Link)
        // L'app mobile détectera 'status=success' pour afficher la confirmation
        $redirectUrl = "maparoisse://paiement?status=success&reference={$transactionId}";

        // 5. Retour de la vue web (page de confirmation intermédiaire)
        return view('paiement.success', [
            'redirectUrl' => $redirectUrl,
            'paiement' => $paiement,
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
            'message' => 'Vous avez annulé le paiement.',
        ]);
    }
}
