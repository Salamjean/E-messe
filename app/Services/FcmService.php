<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $projectId;

    public function __construct()
    {
        // S'assurer que projectId est bien défini
        $this->projectId = env('FIREBASE_PROJECT_ID');
        
        if (empty($this->projectId)) {
            Log::error('FIREBASE_PROJECT_ID est vide dans .env');
            throw new \Exception('FIREBASE_PROJECT_ID non configuré');
        }
        
        Log::info('FcmService initialisé', ['project_id' => $this->projectId]);
    }

    public function send($token, $title, $body, $data = [], $userId = null)
    {
        // Valider que projectId est disponible
        if (empty($this->projectId)) {
            Log::error('Project ID manquant lors de l\'envoi FCM');
            return null;
        }

        if (empty($token)) {
            Log::warning('Token FCM vide');
            return null;
        }

        try {
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

            // Log cohérent avec le project_id
            Log::info('Envoi FCM', [
                'url' => $url,
                'project_id' => $this->projectId, // Utiliser $this->projectId directement
                'token_length' => strlen($token),
                'user_id' => $userId
            ]);

            $accessToken = $this->getAccessToken(); // Votre méthode d'authentification
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->failed()) {
                $errorBody = $response->json();
                
                // Gérer spécifiquement les tokens invalides
                if (isset($errorBody['error']['details'][0]['errorCode']) && 
                    $errorBody['error']['details'][0]['errorCode'] === 'UNREGISTERED') {
                    
                    Log::warning('Token FCM invalid (UNREGISTERED)', [
                        'token_prefix' => substr($token, 0, 20) . '...',
                        'user_id' => $userId
                    ]);
                    
                    if ($userId) {
                        $this->handleInvalidToken($userId);
                    }
                    return ['error' => 'UNREGISTERED'];
                }
                
                Log::error('Erreur FCM', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'project_id' => $this->projectId // Utiliser $this->projectId
                ]);
                return null;
            }

            Log::info('Notification FCM envoyée avec succès', [
                'project_id' => $this->projectId,
                'user_id' => $userId
            ]);
            
            return $response->json();

        } catch (\Exception $e) {
            Log::error('Exception FCM: ' . $e->getMessage(), [
                'project_id' => $this->projectId,
                'user_id' => $userId
            ]);
            return null;
        }
    }

    protected function handleInvalidToken($userId)
    {
        try {
            \App\Models\User::where('id', $userId)->update(['fcm_token' => null]);
            Log::info('Token FCM invalid supprimé', ['user_id' => $userId]);
        } catch (\Exception $e) {
            Log::error('Erreur suppression token', ['user_id' => $userId, 'error' => $e->getMessage()]);
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