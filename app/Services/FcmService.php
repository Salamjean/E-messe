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
        $projectId = env('FIREBASE_PROJECT_ID'); // Doit être: emesse-c9236

        if (!file_exists($credentialsPath)) {
            Log::error('FCM: Fichier JSON manquant');
            return null;
        }

        try {
            // 1. Client sans vérification SSL (pour éviter l'erreur cURL 60 locale)
            $httpClient = new Client(['verify' => false]);

            // 2. Auth Google
            $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
            $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);
            
            $handler = function ($request, $options = []) use ($httpClient) {
                return $httpClient->send($request, $options);
            };
            
            $accessToken = $credentials->fetchAuthToken($handler)['access_token'];

            // 3. Conversion des data en string (Obligatoire pour FCM)
            $dataStrings = array_map(fn($v) => (string) $v, $data);

            // 4. Construction du Payload EXACTEMENT comme ton Postman
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => $dataStrings,
                    // Ajout des configurations Android (Priorité haute)
                    'android' => [
                        'priority' => 'high'
                    ],
                    // Ajout des configurations iOS (Son)
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default'
                            ]
                        ]
                    ]
                ]
            ];

            // 5. Envoi
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $response = Http::withoutVerifying()
                ->withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->failed()) {
                Log::error('FCM Error', ['response' => $response->body()]);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('FCM Exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}