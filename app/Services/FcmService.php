<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client; // IMPORTANT

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
            // 1. Configuration pour ignorer le SSL (POUR TEST LOCAL UNIQUEMENT)
            // On crée un client HTTP qui ne vérifie pas les certificats
            $httpClient = new Client([
                'verify' => false
            ]);

            // 2. On prépare l'authentification Google
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);

            // 3. On force Google Auth à utiliser notre client "sans SSL"
            // Cette fonction anonyme remplace le handler par défaut
            $handler = function ($request, $options) use ($httpClient) {
                return $httpClient->send($request, $options);
            };

            // On récupère le token
            $accessToken = $credentials->fetchAuthToken($handler)['access_token'];

            // 4. Préparation du Payload
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

            // 5. Envoi de la requête finale (en désactivant aussi le SSL ici)
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withoutVerifying() // <--- Désactive SSL pour Laravel HTTP
                ->withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}