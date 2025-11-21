<?php

namespace App\Services;

use Google\Client as Google_Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->projectId = env('FIREBASE_PROJECT_ID');
        $this->initializeAuth();
    }

    protected function initializeAuth()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');
        
        if (!file_exists($credentialsPath)) {
            throw new \Exception('Fichier de credentials Firebase manquant: ' . $credentialsPath);
        }

        try {
            $client = new Google_Client();
            $client->setAuthConfig($credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            
            $this->accessToken = $client->getAccessToken()['access_token'];
            
        } catch (\Exception $e) {
            Log::error('Erreur d\'authentification FCM: ' . $e->getMessage());
            throw $e;
        }
    }

    public function send($token, $title, $body, $data = [])
    {
        if (empty($token)) {
            Log::warning('Token FCM vide');
            return null;
        }

        try {
            // Construction du payload FCM v1
            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $this->formatData($data),
                    'android' => [
                        'priority' => 'high'
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'sound' => 'default',
                                'badge' => 1
                            ]
                        ]
                    ]
                ]
            ];

            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            Log::info('Envoi FCM', [
                'url' => $url,
                'project_id' => $this->projectId,
                'token_length' => strlen($token)
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                Log::error('Erreur FCM', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'project_id' => $this->projectId
                ]);
                return null;
            }

            Log::info('Notification FCM envoyée avec succès');
            return $response->json();

        } catch (\Exception $e) {
            Log::error('Exception FCM: ' . $e->getMessage());
            return null;
        }
    }

    protected function formatData($data)
    {
        $formatted = [];
        foreach ($data as $key => $value) {
            $formatted[$key] = (string) $value;
        }
        return $formatted;
    }
}