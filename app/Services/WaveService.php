<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaveService
{
    protected $apiKey;
    protected $businessId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.wave.api_key');
        $this->businessId = config('services.wave.business_id');
        $this->baseUrl = config('services.wave.env') === 'production' 
            ? 'https://api.wave.com/v1/' 
            : 'https://api.wave.com/v1/';
    }

    /**
     * Créer une session de paiement Wave
     */
    public function createCheckoutSession($amount, $currency, $reference, $redirectUrl, $customer = [])
{
    try {
        $secureRedirectUrl = $this->ensureHttps($redirectUrl);
        
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'success_url' => $secureRedirectUrl . '?status=success&reference=' . $reference,
            'error_url' => $secureRedirectUrl . '?status=error&reference=' . $reference,
            'cancel_url' => $secureRedirectUrl . '?status=cancel&reference=' . $reference,
        ];
        
        // Ajouter les infos client si disponibles
        if (!empty($customer['email'])) {
            $data['customer_email'] = $customer['email'];
        }
        if (!empty($customer['name'])) {
            $data['customer_name'] = $customer['name'];
        }
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . 'checkout/sessions', $data);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        Log::error('Erreur création session Wave', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return null;
        
    } catch (\Exception $e) {
        Log::error('Exception création session Wave: ' . $e->getMessage());
        return null;
    }
}

    /**
     * Forcer les URLs en HTTPS pour le développement
     */
    private function ensureHttps($url)
    {
        if (app()->environment('local') || app()->environment('development')) {
            // Pour le développement local, utilisez un service de tunnel HTTPS comme ngrok
            // ou configurez votre environnement local avec HTTPS
            return preg_replace('/^http:/', 'https:', $url);
        }
        return $url;
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function verifyTransaction($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => app()->environment('production'),
            ])->get($this->baseUrl . 'transactions/' . $transactionId);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('Wave Verification Error: ' . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Wave Verification Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifier le statut par référence marchand - Version corrigée
     */
   /**
 * Vérifier le statut par référence marchand - Version CORRIGÉE
 */
/**
 * Vérifier le statut par référence marchand - Version corrigée
 */
public function verifyByMerchantReference($merchantReference)
{
    try {
        // CORRECTION: Utiliser le bon endpoint avec la référence dans l'URL
        $url = $this->baseUrl . 'checkout/sessions?merchant_reference=' . urlencode($merchantReference);
        
        Log::debug('Requête vérification Wave:', [
            'url' => $url,
            'merchant_reference' => $merchantReference
        ]);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($url); // Utiliser GET avec les paramètres dans l'URL
        
        Log::debug('Réponse vérification Wave:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            // L'API Wave retourne généralement un tableau de sessions
            // On prend la première session trouvée
            return $data['data'][0] ?? null;
        }
        
        Log::error('Erreur vérification Wave', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);
        
        return null;
        
    } catch (\Exception $e) {
        Log::error('Exception vérification Wave: ' . $e->getMessage());
        return null;
    }
}
}