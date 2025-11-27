<?php

namespace App\Http\Controllers\Api\Paiement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Messe;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaiementSuccessNotification;
use App\Notifications\PaiementEchecNotification;

class PaiementController extends Controller
{
    public function initierPaiement(Request $request)
    {
        Log::info('=== DÉBUT INITIATION PAIEMENT CINETPAY (Messe) ===');
        
        // 1. Validation
        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant'  => 'required|numeric|min:100',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        // 2. Génération de la Référence Unique
        // On utilise time() et uniqid() pour garantir qu'aucune collision n'arrive chez CinetPay
        $reference = 'MESSE-' . $messe->id . '-' . time() . '-' . uniqid();
        
        Log::info('🎫 Référence générée', ['reference' => $reference]);

        try {
            // 3. Préparation des URLs (Logique hybride App Mobile / Web)
            $baseUrl = config('app.url'); // ex: https://api.sancta-missa.com
            
            // A. URLs pour le navigateur (Fallback)
            $fallbackReturnUrl = route('cinetpay.success', ['transaction_id' => $reference]);
            $fallbackCancelUrl = route('cinetpay.cancel', ['transaction_id' => $reference]);
            $notifyUrl = route('cinetpay.webhook');

            // B. Deep Links (Pour redirection directe vers l'app mobile si installée)
            // Remplacez 'sanctamissa://' par le scheme de votre application mobile
            $appScheme = "sanctamissa://payment"; 
            $returnDeepLink = "{$appScheme}?cinetpay=true&transactionId={$reference}";
            $cancelDeepLink = "{$appScheme}?cinetpay=false&transactionId={$reference}";

            Log::info('🔗 URLs configurées', [
                'web_return' => $fallbackReturnUrl,
                'app_return' => $returnDeepLink,
                'notify' => $notifyUrl
            ]);

            // 4. Création en Base de Données
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id'  => $user->id,
                'reference'=> $reference,
                'montant'  => $request->montant,
                'devise'   => 'XOF',
                'methode'  => 'cinetpay',
                'statut'   => 'en_attente',
            ]);

            // 5. Configuration CinetPay
            $cinetpayApiKey = env('CINETPAY_API_KEY');
            $cinetpaySiteId = env('CINETPAY_SITE_ID');

            // 6. Construction du Payload (Data)
            $paymentData = [
                'apikey' => $cinetpayApiKey,
                'site_id' => $cinetpaySiteId,
                'transaction_id' => $reference,
                'amount' => (int)$request->montant,
                'currency' => 'XOF',
                'description' => "Offrande Messe: " . substr($messe->titre, 0, 30), // CinetPay limite parfois la longueur
                
                // URLs
                'notify_url' => $notifyUrl,
                'return_url' => $fallbackReturnUrl, // CinetPay redirigera ici par défaut sur le web
                'cancel_url' => $fallbackCancelUrl,
                
                'mode' => env('CINETPAY_MODE', 'PRODUCTION'),
                'channels' => 'ALL',

                // Infos Client (Avec Fallbacks robustes)
                'customer_name' => $user->name ?? 'Fidele',
                'customer_surname' => $user->lastname ?? 'Anonyme',
                'customer_email' => $user->email ?? 'no-reply@sancta-missa.com',
                'customer_phone_number' => $user->phone ?? '',
                'customer_city' => 'Abidjan', // Champs requis parfois par CinetPay
                'customer_country' => 'CI',
                'customer_zip_code' => '00225'
            ];

            Log::info('🔄 Envoi requête CinetPay', ['payload_partiel' => Arr::except($paymentData, ['apikey'])]);

            // 7. Appel API
            $response = Http::withoutVerifying() // Désactive vérif SSL pour éviter les erreurs courantes locales/serveur
                ->timeout(30)
                ->post('https://api-checkout.cinetpay.com/v2/payment', $paymentData);

            $data = $response->json();

            // 8. Gestion d'erreur stricte (Comme dans l'exemple source)
            if ($response->failed() || ($data['code'] ?? '') !== '201') {
                Log::error('❌ Erreur CinetPay', [
                    'reference' => $reference,
                    'response' => $data
                ]);

                $paiement->update([
                    'statut' => 'echec', 
                    'donnees_transaction' => $data
                ]);

                return response()->json([
                    'statut' => 'error',
                    'message' => 'Échec de l\'initialisation CinetPay: ' . ($data['description'] ?? 'Erreur inconnue'),
                    'details' => $data
                ], 400);
            }

            // 9. Succès
            Log::info('✅ Lien de paiement généré avec succès', ['payment_url' => $data['data']['payment_url']]);

            // Mise à jour locale avec le token de paiement reçu si nécessaire
            $paiement->update(['donnees_transaction' => $data]);

            return response()->json([
                'statut'       => 'success',
                'reference'    => $reference,
                'checkout_url' => $data['data']['payment_url'], // URL Web CinetPay
                // On renvoie aussi les liens profonds pour que le front-end (Mobile) puisse décider comment gérer
                'deep_links' => [
                    'return_url' => $returnDeepLink,
                    'cancel_url' => $cancelDeepLink,
                ],
                'web_links' => [
                    'return_url' => $fallbackReturnUrl,
                    'cancel_url' => $fallbackCancelUrl,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('💥 Exception CinetPay: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'statut' => 'error',
                'message' => 'Erreur interne lors de l\'initiation du paiement.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        } finally {
            Log::info('=== FIN INITIATION PAIEMENT ===');
        }
    }



    /**
     * Webhook appelé par CinetPay (Server-to-Server)
     */
    public function handleWebhook(Request $request)
    {
        // CinetPay envoie souvent les données en x-www-form-urlencoded
        $transactionId = $request->input('cpm_trans_id') ?? $request->input('transaction_id');

        if (!$transactionId) {
            Log::warning("Webhook CinetPay: ID manquant", $request->all());
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('user', 'messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }

        // Vérifier l'état réel via l'API CinetPay (plus sûr que de faire confiance aux POST params)
        $statusData = $this->verifyCinetPayStatus($transactionId, $paiement->site_id ?? env('CINETPAY_SITE_ID'));

        if ($statusData['status'] === 'ACCEPTED') {
            if ($paiement->statut !== 'paye') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => array_merge($paiement->donnees_transaction ?? [], $statusData),
                ]);

                if ($paiement->messe) {
                    $paiement->messe->update(['statut' => 'en attente']);
                }

                if ($paiement->user) {
                    $paiement->user->notify(new PaiementSuccessNotification($paiement));
                }
            }
            return response()->json(['message' => 'Paiement validé']);

        } elseif ($statusData['status'] === 'REFUSED') {
            $paiement->update(['statut' => 'echec', 'donnees_transaction' => $statusData]);
            
            if ($paiement->user) {
                $paiement->user->notify(new PaiementEchecNotification($paiement));
            }
            return response()->json(['message' => 'Paiement échoué']);
        }

        return response()->json(['message' => 'Statut en attente ou inconnu']);
    }

    /**
     * Helper pour vérifier le statut via l'API CinetPay
     */
    public static function verifyCinetPayStatus($transactionId, $siteId)
    {
        try {
            $response = Http::post('https://api-checkout.cinetpay.com/v2/payment/check', [
                'apikey'         => env('CINETPAY_API_KEY'),
                'site_id'        => $siteId,
                'transaction_id' => $transactionId,
            ]);
            
            $data = $response->json();
            return [
                'status' => $data['data']['status'] ?? 'UNKNOWN',
                'details' => $data
            ];
        } catch (\Exception $e) {
            Log::error("Erreur Check CinetPay: " . $e->getMessage());
            return ['status' => 'ERROR', 'details' => $e->getMessage()];
        }
    }
}