<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmService
{
    public function send($token, $title, $body, $data = [])
    {
        // 1. On charge la clé PRIVÉE du serveur (pas celle du frontend)
        $credentialsPath = storage_path('app/firebase_credentials.json');
        $projectId = env('FIREBASE_PROJECT_ID');

        if (!file_exists($credentialsPath)) {
            Log::error('FCM: Fichier JSON manquant coté serveur Laravel');
            return null;
        }

        // 2. On demande à Google un token temporaire pour envoyer le message
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
        $credentials = new ServiceAccountCredentials($scopes, $credentialsPath);
        $accessToken = $credentials->fetchAuthToken()['access_token'];

        // 3. On envoie la requête
        $payload = [
            'message' => [
                'token' => $token, // Le token reçu du Frontend
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                // Conversion des données en string (obligatoire pour FCM v1)
                'data' => array_map(fn($v) => (string) $v, $data),
            ]
        ];

        $response = Http::withToken($accessToken) // Utilisation du token serveur
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $payload);

        return $response->json();
    }
}