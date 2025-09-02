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

    public function verifyBySessionId($sessionId)
    {
        try {
            $url = $this->baseUrl . 'checkout/sessions/' . $sessionId;
            
            Log::debug('Vérification session Wave:', ['session_id' => $sessionId]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->get($url);
            
            Log::debug('Réponse vérification session:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Ajoutez du logging pour voir la structure de la réponse
                Log::debug('Structure réponse Wave:', [
                    'keys' => array_keys($data),
                    'has_status' => isset($data['status']),
                    'has_state' => isset($data['state']),
                    'data' => $data
                ]);
                
                return $data;
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Exception vérification session: ' . $e->getMessage());
            return null;
        }
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
    public function verifyByMerchantReference($reference)
    {
        try {
            Log::debug('Début vérification transaction Wave', ['reference' => $reference]);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => app()->environment('production'),
            ])->get($this->baseUrl . 'transactions', [
                // 'merchant_reference' => $reference,
                // 'limit' => 1 // Limiter à 1 résultat
            ]);

            Log::debug('Réponse vérification Wave', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::debug('Données transaction Wave', ['data' => $data]);
                
                // Structure de réponse typique de Wave API
                if (isset($data['data']['transactions']) && count($data['data']['transactions']) > 0) {
                    return $data['data']['transactions'][0];
                }
                
                // Autre format possible
                if (isset($data['data']) && count($data['data']) > 0) {
                    return $data['data'][0];
                }
                
                // Format direct
                if (isset($data['status'])) {
                    return $data;
                }
                
                Log::warning('Aucune transaction trouvée pour la référence', ['reference' => $reference]);
                return null;
            } else {
                Log::error('Erreur vérification Wave', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception vérification Wave: ' . $e->getMessage());
            return null;
        }
    }
}