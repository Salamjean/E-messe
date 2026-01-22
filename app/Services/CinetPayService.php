<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CinetPayService
{
    protected $baseUrl = 'https://api-checkout.cinetpay.com/v2/payment';

    protected $transferBaseUrl = 'https://client.cinetpay.com/v1';

    protected $apiKey;

    protected $siteId;

    protected $password;

    public function __construct()
    {
        $this->apiKey = trim(env('CINETPAY_API_KEY'));
        $this->siteId = trim(env('CINETPAY_SITE_ID'));
        $this->password = trim(env('CINETPAY_PASSWORD'));
    }

    /**
     * Authentifier pour le service de transfert
     */
    protected function authenticateTransfer()
    {
        Log::info('Authentification CinetPay Transfer en cours...');
        try {
            $response = Http::withoutVerifying()->asForm()->post($this->transferBaseUrl.'/auth/login', [
                'apikey' => $this->apiKey,
                'password' => $this->password,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['data']['token'])) {
                Log::info('Authentification CinetPay Transfer réussie.');

                return $result['data']['token'];
            }

            Log::error('Erreur lors de l\'authentification CinetPay Transfer', ['response' => $result]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception lors de l\'authentification CinetPay Transfer', [
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Envoyer de l'argent via CinetPay Transfer API
     */
    public function sendMoney($prefix, $phone, $amount, $transactionId, $contactName = 'Client')
    {
        Log::info("Préparation de l'envoi d'argent (sendMoney) vers $phone", ['ref' => $transactionId]);

        $token = $this->authenticateTransfer();

        if (! $token) {
            Log::error('Impossible de procéder à l\'envoi : Jeton (token) non obtenu.');

            return [
                'code' => 'AUTH_ERROR',
                'msg' => 'Erreur d\'authentification CinetPay',
            ];
        }

        try {
            $transferUrl = $this->transferBaseUrl.'/transfer/money/send/contact?token='.$token;

            $payload = [
                'prefix' => $prefix,
                'phone' => $phone,
                'amount' => (int) $amount,
                'client_transaction_id' => $transactionId,
                'notify_url' => env('CINETPAY_NOTIFY_URL'), // Utilisation de l'URL du .env
            ];

            Log::info('🚀 CinetPay Transfer: Envoi de la requête', $payload);

            $response = Http::withoutVerifying()->asForm()->timeout(60)->post($transferUrl, $payload);
            $result = $response->json();

            Log::info('Réponse brute reçue de CinetPay Transfer (sendMoney):', [
                'status_code' => $response->status(),
                'body' => $result,
            ]);

            if ($response->successful() && isset($result['code'])) {
                return $result;
            }

            Log::error('Erreur rapportée par CinetPay Transfer (API Error)', [
                'status_code' => $response->status(),
                'response' => $result,
            ]);

            return [
                'code' => 'TRANSFER_ERROR',
                'msg' => $result['message'] ?? 'Erreur lors du transfert',
                'details' => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Exception fatale lors de l\'envoi d\'argent CinetPay Transfer', [
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'code' => 'EXCEPTION',
                'msg' => 'Erreur de connexion au service de transfert',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Initialiser un paiement CinetPay (Checkout V2)
     *
     * @param  string  $transactionId  Reference unique
     * @param  int  $amount  Montant
     * @param  array  $urls  Les URLs de retour (notify, return, cancel)
     * @param  array  $customerInfo  Infos client (nom, prenom, email, etc)
     * @return array
     */
    public function initPayment($transactionId, $amount, $urls, $customerInfo = [])
    {
        // Préparation du payload strict
        $data = [
            'apikey' => $this->apiKey,
            'site_id' => $this->siteId,
            'transaction_id' => $transactionId,
            'amount' => (int) $amount,
            'currency' => 'XOF',
            'description' => 'Paiement E-Messe '.$transactionId,
            'notify_url' => $urls['notify_url'],
            'return_url' => $urls['return_url'],
            'cancel_url' => $urls['cancel_url'],
            'channels' => 'ALL',
            'lang' => 'fr',
            'metadata' => 'source_api',
            // Données client
            'customer_name' => $customerInfo['nom'] ?? 'Fidele',
            'customer_surname' => $customerInfo['prenom'] ?? 'Inconnu',
            'customer_email' => $customerInfo['email'] ?? null,
            'customer_phone_number' => $customerInfo['telephone'] ?? '',
            'customer_address' => $customerInfo['adresse'] ?? 'Abidjan',
            'customer_city' => $customerInfo['ville'] ?? 'Abidjan',
            'customer_country' => 'CI',
            'customer_zip_code' => '00225',
        ];

        Log::info('🚀 CinetPayService: Envoi demande paiement', ['ref' => $transactionId, 'amount' => $amount]);

        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl, $data);

            $body = $response->json();

            Log::info('Réponse brute reçue de CinetPay (initPayment):', [
                'status_code' => $response->status(),
                'body' => $body,
            ]);

            if ($response->successful() && isset($body['code']) && $body['code'] == '201') {
                Log::info('✅ CinetPayService: Lien généré', ['url' => $body['data']['payment_url']]);

                return [
                    'success' => true,
                    'payment_url' => $body['data']['payment_url'],
                    'api_response' => $body,
                ];
            } else {
                Log::error('❌ CinetPayService: Erreur API', ['body' => $body]);

                return [
                    'success' => false,
                    'message' => $body['description'] ?? 'Erreur inconnue CinetPay',
                    'details' => $body,
                ];
            }

        } catch (\Exception $e) {
            Log::error('💥 CinetPayService: Exception', ['msg' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function checkStatus($transactionId)
    {
        try {
            $response = Http::withoutVerifying()->post($this->baseUrl.'/check', [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $transactionId,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return ['code' => 'ERROR', 'message' => $e->getMessage()];
        }
    }
}
