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
            // 1. Client Guzzle sans vérification SSL
            $httpClient = new Client([
                'verify' => false, // Désactive SSL pour le token
            ]);

            // 2. Configuration Google Auth
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);

            // 3. Le Handler CORRIGÉ (Ajout de " = []" pour rendre options facultatif)
            $handler = function ($request, $options = []) use ($httpClient) {
                return $httpClient->send($request, $options);
            };

            // 4. Récupération du token avec le handler personnalisé
            $accessToken = $credentials->fetchAuthToken($handler)['access_token'];

            // 5. Envoi de la notification (SSL désactivé aussi ici)
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => array_map(fn($v) => (string) $v, $data),
                ]
            ];

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withoutVerifying() // Désactive SSL pour l'envoi final
                ->withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            return $response->json();

        } catch (\Exception $e) {
            // On log l'erreur exacte pour le debug
            Log::error('FCM Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}