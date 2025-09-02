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
        // Assure-toi que la configuration de l'environnement est correcte
        $this->baseUrl = config('services.wave.env') === 'production'
            ? 'https://api.wave.com/v1/'
            : 'https://api.wave.com/v1/'; // Pour le sandbox/staging, l'URL peut être différente si Wave a un environnement de test dédié. Si c'est la même URL, tant pis.
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
                'redirect_url' => $redirectUrl,
                'customer' => $customer
            ]);

            // Wave exige souvent HTTPS pour les URLs de redirection.
            // En local, tu devras utiliser ngrok ou un setup HTTPS.
            $secureRedirectUrl = $this->ensureHttps($redirectUrl);

            // Wave a parfois des attentes spécifiques pour les paramètres.
            // merchant_reference n'est pas toujours supporté dans ce endpoint.
            // On l'ajoute comme metadata si possible, sinon on le gère via les URLs de redirection.
            $metadata = [
                'merchant_reference' => $reference,
                'email' => $customer['email'] ?? null,
                'name' => $customer['name'] ?? null,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->withOptions([
                'verify' => !app()->environment('local'), // Désactiver SSL en local si tu n'as pas de certificat valide
            ])->post($this->baseUrl . 'checkout/sessions', [
                'amount' => (int)($amount * 100), // Généralement, les montants des paiements sont en cents/centimes
                'currency' => $currency,
                'success_url' => $secureRedirectUrl . '?status=success&reference=' . $reference,
                'error_url' => $secureRedirectUrl . '?status=error&reference=' . $reference,
                // Wave n'a pas explicitement de champ pour merchant_reference ici, on le passe en metadata ou via URL de retour
                // Les détails du client peuvent être passés comme metadata si le support existe.
                'client_reference' => $reference, // Certains gateways utilisent cela comme référence unique
                'metadata' => $metadata // Passe la référence comme metadata si Wave le supporte
            ]);

            Log::debug('Réponse de l\'API Wave pour createCheckoutSession', [
                'status' => $response->status(),
                'body' => $response->json() // Utiliser json() ici car body() est une string
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Session Wave créée avec succès', ['session_id' => $data['id'] ?? null]);
                return $data;
            } else {
                Log::error('Wave API Error pour createCheckoutSession', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'reference' => $reference
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Wave Service Exception dans createCheckoutSession', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Forcer les URLs en HTTPS pour le développement
     */
    private function ensureHttps($url)
    {
        // En environnement local, il est courant d'utiliser un tunnel comme ngrok
        // ou de configurer un serveur local HTTPS.
        // Si tu utilises ngrok, l'URL de base est déjà HTTPS.
        // Si tu es sur un serveur local sans HTTPS, Wave refusera la redirection.
        if (app()->environment('local')) {
            // Supposons que tu utilises ngrok ou un service similaire qui donne une URL HTTPS
            // Remplace 'http://localhost' par l'URL de ton tunnel si nécessaire.
            return str_replace('http://', 'https://', $url);
        }
        return $url;
    }

    /**
     * Vérifier le statut d'une transaction par son ID (l'ID de session de checkout)
     */
    public function verifyTransaction($transactionId)
    {
        try {
            Log::debug('Début vérification transaction Wave par ID', ['transaction_id' => $transactionId]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => !app()->environment('local'),
            ])->get($this->baseUrl . 'checkout/sessions/' . $transactionId); // Utilise l'endpoint pour les sessions de checkout

            Log::debug('Réponse vérification Wave par ID', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // La structure de la réponse d'une session de checkout contient le statut de la transaction
                // Vérifie la documentation de l'API Wave pour la structure exacte.
                // Généralement, il y aura un champ 'status' ou 'transaction' à l'intérieur de la session.
                return $data; // Retourne la session complète, le controller extraira le statut.
            } else {
                Log::error('Erreur vérification Wave par ID', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                    'transaction_id' => $transactionId
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception vérification Wave par ID: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Cette méthode ne sera plus utilisée car l'API Wave ne supporte pas le filtrage par merchant_reference sur /transactions
     * Nous nous baserons sur l'ID de transaction (session ID) pour la vérification.
     */
    public function verifyByMerchantReference($reference)
    {
        // Cette méthode est obsolète ou son usage incorrect pour l'API Wave actuelle.
        // Nous allons logguer un avertissement si elle est appelée accidentellement.
        Log::warning('Appel obsolète ou incorrect de verifyByMerchantReference. Utiliser verifyTransaction avec l\'ID de session.');
        return null;
    }
}