<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CinetPayService
{
    protected $baseUrl = 'https://api-checkout.cinetpay.com/v2/payment';
    protected $apiKey;
    protected $siteId;

    public function __construct()
    {
        $this->apiKey = env('CINETPAY_API_KEY');
        $this->siteId = env('CINETPAY_SITE_ID');
    }

    /**
     * Initialiser un paiement CinetPay
     * 
     * @param string $transactionId Reference unique
     * @param int $amount Montant
     * @param array $urls Les URLs de retour (notify, return, cancel)
     * @param array $customerInfo Infos client (nom, prenom, email, etc)
     * @return array
     */
    public function initPayment($transactionId, $amount, $urls, $customerInfo = [])
    {
        // Préparation du payload strict
        $data = [
            'apikey' => $this->apiKey,
            'site_id' => $this->siteId,
            'transaction_id' => $transactionId,
            'amount' => (int)$amount,
            'currency' => 'XOF',
            'description' => 'Paiement E-Messe ' . $transactionId,
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

            if ($response->successful() && isset($body['code']) && $body['code'] == '201') {
                Log::info('✅ CinetPayService: Lien généré', ['url' => $body['data']['payment_url']]);
                return [
                    'success' => true,
                    'payment_url' => $body['data']['payment_url'],
                    'api_response' => $body
                ];
            } else {
                Log::error('❌ CinetPayService: Erreur API', ['body' => $body]);
                return [
                    'success' => false,
                    'message' => $body['description'] ?? 'Erreur inconnue CinetPay',
                    'details' => $body
                ];
            }

        } catch (\Exception $e) {
            Log::error('💥 CinetPayService: Exception', ['msg' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erreur de connexion au service de paiement',
                'details' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function checkStatus($transactionId)
    {
        try {
            $response = Http::withoutVerifying()->post($this->baseUrl . '/check', [
                'apikey' => $this->apiKey,
                'site_id' => $this->siteId,
                'transaction_id' => $transactionId
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return ['code' => 'ERROR', 'message' => $e->getMessage()];
        }
    }
}
