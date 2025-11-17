<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseConfirmeeNotification extends Notification
{
    use Queueable;

    protected $messe;

    /**
     * Constructeur
     */
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
        $motif = $this->messe->motif_intention ?? 'votre intention';

        return [
            'type'      => 'messe_confirmee',
            'title'     => 'Messe confirmée',
            'body'      => "Bonne nouvelle ! Votre demande de messe pour « {$motif} » a été confirmée.",
            'messe_id'  => $this->messe->id,
            'motif_intention' => $this->messe->motif_intention,
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

        $motif = $this->messe->motif_intention ?? 'votre intention';
        $title = 'Messe confirmée';
        $body = "Bonne nouvelle ! Votre demande de messe pour « {$motif} » a été confirmée.";

        // Structure FCM corrigée
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'icon' => 'ic_notification',
                'badge' => '1', // Optionnel - pour iOS
            ],
            'data' => [
                'title' => $title,
                'body' => $body,
                'type' => 'messe_confirmee',
                'messe_id' => (string) $this->messe->id,
                'motif_intention' => $this->messe->motif_intention,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'timestamp' => now()->toISOString(),
            ],
            'priority' => 'high',
            'content_available' => true,
            'android' => [
                'priority' => 'high',
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'content-available' => 1,
                        'sound' => 'default',
                        'badge' => 1,
                    ],
                ],
            ],
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
                Log::info('✅ Notification FCM messe confirmée envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'messe_id' => $this->messe->id,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                    'message_id' => $responseData['message_id'] ?? null,
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM messe confirmée', [
                    'user_id' => $notifiable->id,
                    'messe_id' => $this->messe->id,
                    'response' => $responseData,
                    'error' => $responseData['results'][0]['error'] ?? 'Unknown error',
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM messe confirmée', [
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