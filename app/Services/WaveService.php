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
            Log::debug('Tentative de création de session Wave', [
                'amount' => $amount,
                'currency' => $currency,
                'reference' => $reference,
                'redirect_url' => $redirectUrl
            ]);

            // Forcer l'URL en HTTPS pour le développement local
            $secureRedirectUrl = $this->ensureHttps($redirectUrl);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->withOptions([
                'verify' => app()->environment('production'), // SSL seulement en production
            ])->post($this->baseUrl . 'checkout/sessions', [
                'amount' => $amount,
                'currency' => $currency,
                'success_url' => $secureRedirectUrl . '?status=success&reference=' . $reference,
                'error_url' => $secureRedirectUrl . '?status=error&reference=' . $reference,
                // Note: cancel_url et merchant_reference ne sont pas supportés selon l'erreur
            ]);

            Log::debug('Réponse de l\'API Wave', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Session Wave créée avec succès', ['session_id' => $data['id'] ?? null]);
                return $data;
            } else {
                Log::error('Wave API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'reference' => $reference
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Wave Service Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
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
   public function verifyByMerchantReference($merchantReference)
    {
        try {
            $url = $this->baseUrl . '/v1/checkout/sessions/verify/';
            
            // CORRECTION: Envoyer les données dans le body JSON, pas en query params
            $data = [
                'merchant_reference' => $merchantReference
            ];
            
            Log::debug('Requête vérification Wave:', [
                'url' => $url,
                'data' => $data
            ]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $data); // Utiliser POST au lieu de GET avec query params
            
            Log::debug('Réponse vérification Wave:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            if ($response->successful()) {
                return $response->json();
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