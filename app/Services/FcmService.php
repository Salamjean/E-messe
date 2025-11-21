<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class FcmService
{
    public function send($token, $title, $body, $data = [])
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');
        $projectId = env('FIREBASE_PROJECT_ID');

        if (!file_exists($credentialsPath)) {
            Log::error('FCM: Fichier JSON manquant coté serveur Laravel');
            return null;
        }

        try {
            // 1. Client Guzzle sans vérification SSL (Pour ton environnement local Windows)
            $httpClient = new Client([
                'verify' => false, 
            ]);

            // 2. Configuration Google Auth
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);

            // 3. Handler personnalisé pour injecter le client sans SSL
            $handler = function ($request, $options = []) use ($httpClient) {
                return $httpClient->send($request, $options);
            };

            // 4. Récupération du token
            $accessToken = $credentials->fetchAuthToken($handler)['access_token'];

            // 5. Préparation du Payload (Format V1)
            // Conversion des données en string pour éviter les erreurs Java/Android
            $dataStrings = array_map(fn($v) => (string) $v, $data);

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => $dataStrings,
                ]
            ];

            // 6. Envoi final
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withoutVerifying() // Désactive SSL
                ->withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            // Log en cas d'échec
            if ($response->failed()) {
                Log::error('FCM Error V1', [
                    'body' => $response->body(),
                    'status' => $response->status()
                ]);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}