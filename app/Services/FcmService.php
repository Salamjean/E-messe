<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $projectId;
    protected $accessToken;

    public function __construct()
    {
        $this->initializeAuth();
    }

    protected function initializeAuth()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');
        
        if (!file_exists($credentialsPath)) {
            Log::warning('Fichier de credentials Firebase manquant: ' . $credentialsPath);
            return;
        }

        try {
            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $credentialsPath
            );
            $token = $credentials->fetchAuthToken();
            
            $this->accessToken = $token['access_token'] ?? null;
            $this->projectId = env('FIREBASE_PROJECT_ID') ?: $credentials->getProjectId();
            
        } catch (\Throwable $e) {
            Log::error('Erreur d\'authentification FCM: ' . $e->getMessage());
        }
    }

    public function send($token, $title, $body, $data = [])
    {
        if (empty($token)) {
            Log::warning('Token FCM vide');
            return null;
        }

        if (empty($this->accessToken) || empty($this->projectId)) {
            Log::error('Impossible d\'envoyer la notification FCM : accessToken ou projectId manquant');
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
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default'
                        ]
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

        } catch (\Throwable $e) {
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