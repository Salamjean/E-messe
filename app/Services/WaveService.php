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
        $this->apiKey = config('services.wave.api_key') ?: env('WAVE_API_KEY');
        $this->businessId = config('services.wave.business_id') ?: env('WAVE_BUSINESS_ID');
        $this->baseUrl = 'https://api.wave.com/v1/';
    }

    /**
     * Créer une session de paiement Wave
     */
    public function createCheckoutSession($amount, $currency = 'XOF', $reference = null, $successUrl = null, $errorUrl = null, array $metadata = [])
    {
        try {
            $payload = [
                'amount' => (string) $amount,
                'currency' => $currency ?: 'XOF',
                'success_url' => $this->formatWaveUrl($successUrl),
                'error_url' => $this->formatWaveUrl($errorUrl),
            ];

            if ($reference) {
                $payload['client_reference'] = (string) $reference;
            }

            // Note: Wave API n'accepte pas de champ 'metadata' dans /checkout/sessions
            // Les métadonnées sont suivies localement via 'client_reference' et en base de données.

            Log::info('WaveService: Initiation session de paiement', [
                'amount' => $amount,
                'reference' => $reference,
                'success_url' => $successUrl,
            ]);

            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->post($this->baseUrl . 'checkout/sessions', $payload);

            $data = $response->json();

            if ($response->successful()) {
                Log::info('WaveService: Session créée avec succès', [
                    'session_id' => $data['id'] ?? null,
                    'wave_launch_url' => $data['wave_launch_url'] ?? null,
                ]);

                return [
                    'success' => true,
                    'data' => $data,
                    'wave_launch_url' => $data['wave_launch_url'] ?? ($data['checkout_url'] ?? null),
                    'session_id' => $data['id'] ?? null,
                ];
            }

            Log::error('WaveService: Erreur API Wave', [
                'status' => $response->status(),
                'response' => $data,
            ]);

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Erreur lors de la création de la session Wave',
                'details' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('WaveService Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Vérifier une session Wave par son ID (ex: cos-xxxx)
     */
    public function verifyBySessionId($sessionId)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->timeout(20)
                ->get($this->baseUrl . 'checkout/sessions/' . $sessionId);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('WaveService: Session non trouvée ou erreur', [
                'session_id' => $sessionId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('WaveService: Exception vérification session : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifier une transaction Wave par son ID
     */
    public function verifyTransaction($transactionId)
    {
        try {
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->timeout(20)
                ->get($this->baseUrl . 'transactions/' . $transactionId);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('WaveService: Exception vérification transaction : ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Valide et adapte une URL aux exigences strictes de Wave :
     * - Protocole https:// obligatoire
     * - Hôte avec TLD obligatoire (rejette localhost ou IP brute)
     */
    public function formatWaveUrl($url)
    {
        if (empty($url)) {
            return $url;
        }

        // 1. Forcer https://
        $url = preg_replace('/^http:/i', 'https:', $url);

        // 2. Vérifier l'hôte
        $parts = parse_url($url);
        $host = $parts['host'] ?? '';

        // Si l'hôte est localhost ou une IP locale sans TLD
        if ($host === 'localhost' || $host === '127.0.0.1' || !str_contains($host, '.')) {
            $fallbackHost = config('services.wave.redirect_host')
                ?: env('WAVE_REDIRECT_HOST', 'sancta-missa.com');

            // Remplacer l'hôte et enlever le port local
            $url = preg_replace('#https?://[^/]+#i', 'https://' . $fallbackHost, $url);
        }

        return $url;
    }
}