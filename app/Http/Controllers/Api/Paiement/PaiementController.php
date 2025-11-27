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
        $request->validate([
            'messe_id' => 'required|exists:messes,id',
            'montant'  => 'required|numeric|min:100',
        ]);

        $user = $request->user();
        $messe = Messe::findOrFail($request->messe_id);

        // Référence unique selon le format CinetPay
        $reference = 'MESSE_' . time() . '_' . $user->id . '_' . rand(1000, 9999);

        try {
            // Création locale du paiement
            $paiement = Paiement::create([
                'messe_id' => $messe->id,
                'user_id'  => $user->id,
                'reference'=> $reference,
                'montant'  => $request->montant,
                'devise'   => 'XOF',
                'methode'  => 'cinetpay',
                'statut'   => 'en_attente',
            ]);

            // URLs de callback
            $returnUrl = route('cinetpay.success', ['transaction_id' => $reference]);
            $cancelUrl = route('cinetpay.cancel', ['transaction_id' => $reference]);
            $notifyUrl = route('cinetpay.webhook');

            // Préparation des données selon la documentation CinetPay
            $payload = [
                'apikey'                 => env('CINETPAY_API_KEY'),
                'site_id'                => env('CINETPAY_SITE_ID'),
                'transaction_id'         => $reference,
                'amount'                 => (int)$request->montant,
                'currency'               => 'XOF',
                'description'            => 'Offrande de Messe - ' . ($messe->titre ?? 'Messe'),
                'customer_id'            => (string)$user->id,
                'customer_name'          => $user->name ?: 'Fidele',
                'customer_surname'       => $user->name ?: 'Fidele',
                'customer_email'         => $user->email ?: 'no-reply@emesse.com',
                'customer_phone_number'  => $user->phone ?? '+225000000000',
                'customer_address'       => $user->adresse ?? 'Abidjan',
                'customer_city'          => $user->ville ?? 'Abidjan',
                'customer_country'       => 'CI', // Code pays par défaut
                'customer_state'         => 'CI', // Code état par défaut
                'customer_zip_code'      => $user->code_postal ?? '00225',
                'return_url'             => $returnUrl,
                'cancel_url'             => $cancelUrl,
                'notify_url'             => $notifyUrl,
                'channels'               => 'ALL',
                'metadata'               => json_encode([
                    'user_id' => $user->id,
                    'messe_id' => $messe->id,
                    'paiement_id' => $paiement->id,
                    'type' => 'offrande_messe'
                ]),
                'lang'                   => 'FR',
                'invoice_data'           => [
                    'Messe' => $messe->titre ?? 'Messe',
                    'Date'  => now()->format('d/m/Y'),
                    'Type'  => 'Offrande'
                ]
            ];

            // Log sécurisé
            $this->safeLog("Envoi à CinetPay", [
                'reference' => $reference,
                'montant' => $request->montant,
                'user_id' => $user->id
            ]);

            // Appel CinetPay avec headers corrects
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->withOptions(['verify' => false])
                ->post('https://api-checkout.cinetpay.com/v2/payment', $payload);

            $data = $response->json();

            // Journalisation de la réponse
            $this->safeLog("Réponse CinetPay", [
                'reference' => $reference,
                'status_code' => $response->status(),
                'has_payment_url' => isset($data['data']['payment_url'])
            ]);

            // Gestion d'erreur améliorée
            if (!$response->successful()) {
                $this->safeLog("Erreur HTTP CinetPay", [
                    'status' => $response->status(),
                    'reference' => $reference,
                    'error' => $data['message'] ?? 'Erreur inconnue'
                ]);
                
                $paiement->update([
                    'statut' => 'echec', 
                    'donnees_transaction' => $data
                ]);

                return response()->json([
                    'message' => 'Erreur de connexion avec le service de paiement',
                    'details' => $data['message'] ?? 'Erreur inconnue',
                    'code' => $data['code'] ?? 'HTTP_ERROR'
                ], 400);
            }

            // Vérification de la réponse CinetPay
            if ($data['code'] != '201') {
                $this->safeLog("Erreur CinetPay API", [
                    'reference' => $reference,
                    'code' => $data['code'],
                    'message' => $data['message'] ?? $data['description'] ?? 'Erreur inconnue'
                ]);
                
                $paiement->update([
                    'statut' => 'echec', 
                    'donnees_transaction' => $data
                ]);

                return response()->json([
                    'message' => "Erreur CinetPay: " . ($data['message'] ?? $data['description'] ?? 'Erreur inconnue'),
                    'code' => $data['code'] ?? 'UNKNOWN_ERROR',
                    'details' => $data
                ], 400);
            }

            if (!isset($data['data']['payment_url'])) {
                $this->safeLog("Payment URL manquante", [
                    'reference' => $reference,
                    'response' => $data
                ]);
                
                $paiement->update([
                    'statut' => 'echec', 
                    'donnees_transaction' => $data
                ]);

                return response()->json([
                    'message' => "Erreur: URL de paiement non reçue",
                    'code' => 'MISSING_PAYMENT_URL',
                    'details' => $data
                ], 400);
            }

            // Succès - mise à jour du paiement
            $paiement->update([
                'donnees_transaction' => $data,
                'url_paiement' => $data['data']['payment_url']
            ]);

            return response()->json([
                'statut'       => 'success',
                'message'      => 'Paiement initié avec succès',
                'reference'    => $reference,
                'checkout_url' => $data['data']['payment_url'],
                'data'         => [
                    'payment_url' => $data['data']['payment_url'],
                    'transaction_id' => $reference,
                    'amount' => $request->montant
                ]
            ]);

        } catch (\Exception $e) {
            $this->safeLog("Exception CinetPay", [
                'error' => $e->getMessage(),
                'reference' => $reference ?? 'unknown'
            ]);
            
            return response()->json([
                'message' => 'Erreur serveur lors de l\'initialisation du paiement',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
            ], 500);
        }
    }

    /**
     * Webhook CinetPay (Server-to-Server)
     */
    public function handleWebhook(Request $request)
    {
        $this->safeLog("Webhook CinetPay reçu", $request->all());

        // CinetPay envoie les données en POST
        $transactionId = $request->input('cpm_trans_id') ?? $request->input('transaction_id');
        $siteId = $request->input('cpm_site_id') ?? $request->input('site_id');

        if (!$transactionId) {
            $this->safeLog("Webhook: Transaction ID manquant", $request->all());
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('user', 'messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            $this->safeLog("Webhook: Transaction inconnue", ['transaction_id' => $transactionId]);
            return response()->json(['message' => 'Transaction inconnue'], 404);
        }

        // Vérification du statut via l'API CinetPay
        $statusData = $this->verifyCinetPayStatus($transactionId, $siteId);

        $this->safeLog("Statut vérifié CinetPay", [
            'transaction_id' => $transactionId,
            'status' => $statusData['status']
        ]);

        if ($statusData['status'] === 'ACCEPTED') {
            if ($paiement->statut !== 'paye') {
                $paiement->update([
                    'statut' => 'paye',
                    'date_paiement' => now(),
                    'donnees_transaction' => array_merge($paiement->donnees_transaction ?? [], $statusData),
                ]);

                // Mettre à jour le statut de la messe
                if ($paiement->messe) {
                    $paiement->messe->increment('montant_collecte', $paiement->montant);
                }

                // Notification
                if ($paiement->user) {
                    try {
                        $paiement->user->notify(new PaiementSuccessNotification($paiement));
                    } catch (\Exception $e) {
                        $this->safeLog("Erreur notification succès", [
                            'paiement_id' => $paiement->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                $this->safeLog("Paiement accepté", [
                    'transaction_id' => $transactionId,
                    'paiement_id' => $paiement->id
                ]);
            }
            return response()->json(['message' => 'Paiement validé']);

        } elseif (in_array($statusData['status'], ['REFUSED', 'CANCELED', 'FAILED'])) {
            $paiement->update([
                'statut' => 'echec', 
                'donnees_transaction' => $statusData
            ]);
            
            if ($paiement->user) {
                try {
                    $paiement->user->notify(new PaiementEchecNotification($paiement));
                } catch (\Exception $e) {
                    $this->safeLog("Erreur notification échec", [
                        'paiement_id' => $paiement->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $this->safeLog("Paiement échoué", [
                'transaction_id' => $transactionId,
                'status' => $statusData['status']
            ]);
            
            return response()->json(['message' => 'Paiement échoué']);
        }

        // Statut en attente (INITIATED, PENDING, etc.)
        $this->safeLog("Paiement en attente", [
            'transaction_id' => $transactionId,
            'status' => $statusData['status']
        ]);
        
        return response()->json(['message' => 'Paiement en attente']);
    }

    /**
     * Vérification du statut via API CinetPay
     */
    public static function verifyCinetPayStatus($transactionId, $siteId = null)
    {
        try {
            $siteId = $siteId ?: env('CINETPAY_SITE_ID');
            
            $response = Http::timeout(15)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->withOptions(['verify' => false])
                ->post('https://api-checkout.cinetpay.com/v2/payment/check', [
                    'apikey'         => env('CINETPAY_API_KEY'),
                    'site_id'        => $siteId,
                    'transaction_id' => $transactionId,
                ]);
            
            $data = $response->json();
            
            return [
                'status' => $data['data']['status'] ?? 'UNKNOWN',
                'message' => $data['message'] ?? '',
                'details' => $data
            ];
            
        } catch (\Exception $e) {
            error_log("Erreur vérification CinetPay: " . $e->getMessage());
            return [
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'details' => []
            ];
        }
    }

    /**
     * URL de retour succès
     */
    public function success(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('cpm_trans_id');
        
        if (!$transactionId) {
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::with('messe')->where('reference', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['message' => 'Paiement non trouvé'], 404);
        }

        return response()->json([
            'message' => 'Paiement effectué avec succès',
            'statut' => $paiement->statut,
            'paiement' => [
                'id' => $paiement->id,
                'reference' => $paiement->reference,
                'montant' => $paiement->montant,
                'date_paiement' => $paiement->date_paiement,
                'messe' => $paiement->messe ? $paiement->messe->titre : null
            ]
        ]);
    }

    /**
     * URL d'annulation
     */
    public function cancel(Request $request)
    {
        $transactionId = $request->input('transaction_id') ?? $request->input('cpm_trans_id');
        
        if (!$transactionId) {
            return response()->json(['message' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::where('reference', $transactionId)->first();

        if ($paiement && $paiement->statut === 'en_attente') {
            $paiement->update(['statut' => 'annule']);
        }

        return response()->json([
            'message' => 'Paiement annulé',
            'statut' => 'annule',
            'transaction_id' => $transactionId
        ]);
    }

    /**
     * Log sécurisé avec fallback
     */
    private function safeLog($message, $context = [])
    {
        try {
            // Ne pas logger les données sensibles
            $safeContext = array_diff_key($context, array_flip(['apikey', 'password', 'token']));
            Log::info($message, $safeContext);
        } catch (\Exception $e) {
            // Fallback vers error_log
            error_log("LOG: {$message} - " . json_encode($context));
        }
    }
}