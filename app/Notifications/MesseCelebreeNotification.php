<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseCelebreeNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Canaux : database + FCM
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Données enregistrées dans la base
     */
    public function toArray($notifiable)
    {
        return [
            'type'     => 'messe_celebree',
            'title'    => 'Messe célébrée',
            'body'     => "Votre demande de messe pour « {$this->messe->motif_intention} » a été célébrée.",
            'messe_id' => $this->messe->id,
        ];
    }

    /**
     * Notification FCM corrigée
     */
    public function toFcm($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            Log::warning('Aucun token FCM pour l\'utilisateur', ['user_id' => $notifiable->id]);
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');
        
        if (!$serverKey) {
            Log::error('Clé FIREBASE_SERVER_KEY manquante dans .env');
            return null;
        }

        $title = 'Messe célébrée';
        $body = "Votre demande de messe pour « {$this->messe->motif_intention} » a été célébrée.";

        // Structure FCM corrigée
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'icon' => 'ic_notification', // Optionnel - icône pour Android
            ],
            'data' => [
                'title' => $title,           // Doublon pour compatibilité
                'body' => $body,             // Doublon pour compatibilité
                'type' => 'messe_celebree',
                'messe_id' => (string) $this->messe->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'motif_intention' => $this->messe->motif_intention, // Donnée supplémentaire
            ],
            'priority' => 'high',
            'content_available' => true,     // Important pour iOS
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(10)
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

            $responseData = $response->json();

            // Log du résultat
            if ($response->successful() && isset($responseData['success']) && $responseData['success'] == 1) {
                Log::info('✅ Notification FCM messe célébrée envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'messe_id' => $this->messe->id,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM messe célébrée', [
                    'user_id' => $notifiable->id,
                    'messe_id' => $this->messe->id,
                    'response' => $responseData,
                    'error' => $responseData['results'][0]['error'] ?? 'Unknown error',
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM messe célébrée', [
                'user_id' => $notifiable->id,
                'messe_id' => $this->messe->id,
                'error' => $e->getMessage(),
                'token' => substr($notifiable->fcm_token, 0, 10) . '...',
            ]);
            return null;
        }
    }

    /**
     * Route FCM (optionnel mais recommandé)
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->fcm_token;
    }
}