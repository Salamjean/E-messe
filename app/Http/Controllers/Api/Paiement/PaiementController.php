<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Http\Controllers\Controller;
use App\Models\Messe;
use App\Models\Paiement;
use App\Notifications\PaiementSuccessNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    public function initierPaiement(Request $request)
    {
        Log::info('=== DÉBUT INITIATION PAIEMENT CINETPAY ===');

        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant' => 'required|numeric|min:100',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        // Référence unique avec timestamp pour éviter les doublons
        $reference = 'MESSE_'.time().'_'.$user->id;

        try {
            // 1. URLs de callback
            // NOTE : CinetPay n'accepte pas localhost pour le notify_url.
            // En local, on met une URL bidon si on n'a pas Ngrok, sinon l'API rejette la requête.
            $baseUrl = config('app.url'); // Ton URL publique ou Ngrok
            $isLocal = str_contains($baseUrl, 'localhost') || str_contains($baseUrl, '127.0.0.1');

            $notifyUrl = $isLocal
                ? 'https://google.com'
                : route('cinetpay.webhook');

            $returnUrl = route('cinetpay.success');
            $cancelUrl = route('cinetpay.cancel');

            // 2. Création locale du paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id' => $user->id,
                'reference' => $reference,
                'montant' => $request->montant,
                'devise' => 'XOF',
                'methode' => 'cinetpay',
                'statut' => 'en_attente',
            ]);

            // 3. Formatage Téléphone (International sans +)
            $phone = $user->phone ?? '0707070707';
            $phone = preg_replace('/[^0-9]/', '', $phone); // Garde que les chiffres
            // Si le numéro est ivoirien (10 chiffres), on ajoute 225
            if (strlen($phone) === 10) {
                $phone = '225'.$phone;
            }

            // 4. Préparation payload CinetPay V2
            $paymentData = [
                'apikey' => env('CINETPAY_API_KEY'),
                'site_id' => env('CINETPAY_SITE_ID'),
                'transaction_id' => $reference,
                'amount' => (int) $request->montant,
                'currency' => 'XOF',
                'description' => 'Offrande de Messe #'.$messe->id,
                'notify_url' => $notifyUrl,
                'return_url' => $returnUrl,
                'channels' => 'ALL',
                'metadata' => strval($messe->id), // Utile pour retrouver l'info

                // Informations Client
                'customer_name' => $user->nom ?? 'Fidele', // Nom séparé
                'customer_surname' => $user->prenoms ?? 'E-messe', // Prénoms
                'customer_email' => $user->email ?? 'client@emesse.com',
                'customer_phone_number' => $phone,
                'customer_address' => $user->adresse ?? 'Abidjan',
                'customer_city' => $user->ville ?? 'Abidjan',
                'customer_country' => 'CI',
                'customer_state' => 'CI',
                'customer_zip_code' => '00225',
            ];

            // 5. Appel API
            $response = Http::withoutVerifying()
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://api-checkout.cinetpay.com/v2/payment', $paymentData);

            $result = $response->json();

            // 6. Vérification Réponse
            if ($response->successful() && isset($result['code']) && $result['code'] == '201') {

                $paiement->update(['donnees_transaction' => $result]);

                return response()->json([
                    'statut' => 'success',
                    'reference' => $reference,
                    'payment_url' => $result['data']['payment_url'],
                    'api_response' => $result,
                ]);
            } else {
                Log::error('Erreur CinetPay Init', ['response' => $result]);

                return response()->json([
                    'message' => 'Erreur initialisation CinetPay',
                    'details' => $result['description'] ?? 'Erreur inconnue',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Exception Paiement: '.$e->getMessage());

            return response()->json(['message' => 'Erreur Serveur', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Webhook (Notification)
     * CinetPay envoie une requête POST sur cette route quand le paiement change d'état
     */
    public function handleWebhook(Request $request)
    {
        // CinetPay envoie cpm_trans_id en POST (x-www-form-urlencoded)
        $transactionId = $request->input('cpm_trans_id');
        $siteId = $request->input('cpm_site_id');

        Log::info('Webhook CinetPay Reçu', $request->all());

        if (! $transactionId || ! $siteId) {
            return response()->json(['message' => 'Données manquantes'], 400);
        }

        // Vérification que le Site ID correspond au nôtre (Sécurité basique)
        if ($siteId != env('CINETPAY_SITE_ID')) {
            return response()->json(['message' => 'Site ID invalide'], 400);
        }

        $paiement = Paiement::where('reference', $transactionId)->first();

        if (! $paiement) {
            Log::warning("Webhook: Paiement introuvable pour REF: $transactionId");

            return response()->json(['message' => 'Transaction introuvable'], 404);
        }

        // On vérifie le statut réel auprès de l'API CinetPay (Plus sûr que de se fier juste au POST)
        $verification = $this->verifyCinetPayStatus($transactionId, $siteId);

        if ($verification['status'] === 'ACCEPTED') {
            // Paiement validé
            if ($paiement->statut !== 'paye') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => array_merge($paiement->donnees_transaction ?? [], $verification['details']),
                ]);

                // Mise à jour de la messe
                if ($paiement->messe) {
                    $paiement->messe->update(['statut' => 'paye']); // ou 'en_attente' selon ta logique
                }

                // Notification User
                try {
                    if ($paiement->user) {
                        $paiement->user->notify(new PaiementSuccessNotification($paiement));
                    }
                } catch (\Exception $e) {
                    Log::error('Erreur notif mail: '.$e->getMessage());
                }
            }

            return response()->json(['message' => 'Paiement validé avec succès']);

        } elseif ($verification['status'] === 'REFUSED') {
            $paiement->update(['statut' => 'echec']);

            return response()->json(['message' => 'Paiement échoué']);
        }

        return response()->json(['message' => 'Statut reçu: '.$verification['status']]);
    }

    // Helper de vérification
    private function verifyCinetPayStatus($transactionId, $siteId)
    {
        try {
            $response = Http::withoutVerifying()->post('https://api-checkout.cinetpay.com/v2/payment/check', [
                'apikey' => env('CINETPAY_API_KEY'),
                'site_id' => $siteId,
                'transaction_id' => $transactionId,
            ]);

            $result = $response->json();

            // Log de vérification
            Log::info("Verification CinetPay $transactionId", ['result' => $result]);

            if (isset($result['code']) && $result['code'] == '00') {
                $data = $result['data'];

                // status peut être : ACCEPTED, REFUSED, PENDING
                return [
                    'status' => $data['status'],
                    'details' => $data,
                ];
            }

            return ['status' => 'ERROR', 'details' => $result];

        } catch (\Exception $e) {
            return ['status' => 'ERROR', 'details' => $e->getMessage()];
        }
    }

    // Route de retour (Success)
    public function paymentSuccess(Request $request)
    {
        // Cette route est appelée par le navigateur du client
        // On renvoie juste un JSON ou on redirige vers le frontend
        return response()->json(['message' => 'Retour paiement détecté. En attente de validation webhook.']);
    }

    // Route de retour (Cancel)
    public function paymentCancel(Request $request)
    {
        return response()->json(['message' => 'Paiement annulé par l\'utilisateur.']);
    }
}
