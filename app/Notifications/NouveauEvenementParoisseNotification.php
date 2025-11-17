<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Deux canaux : database + FCM
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Données enregistrées dans la base de données
     */
    public function toArray($notifiable)
    {
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        $title = 'Nouvel événement créé 🎉';
        $body =
            "{$paroisseName} que vous suivez organise un événement : « {$this->event->titre} », " .
            "prévu le " . $this->event->date_debut->format('d/m/Y') . ".";

        return [
            'type'          => 'nouveau_evenement',
            'title'         => $title,
            'body'          => $body,
            'evenement_id'  => $this->event->id,
            'paroisse_id'   => $this->event->paroisse_id ?? $this->event->created_by,
            'paroisse_name' => $paroisseName,
            'event_titre'   => $this->event->titre,
            'event_date'    => $this->event->date_debut->toISOString(),
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

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $this->event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body = "{$paroisseName} organise l'événement « {$this->event->titre} » le {$dateFormatted}.";

        // Structure FCM corrigée
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'icon' => 'ic_notification',
                'badge' => '1',
            ],
            'data' => [
                'title' => $title,
                'body' => $body,
                'type' => 'nouveau_evenement',
                'evenement_id' => (string) $this->event->id,
                'paroisse_id' => (string) ($this->event->paroisse_id ?? $this->event->created_by),
                'paroisse_name' => $paroisseName,
                'event_titre' => $this->event->titre,
                'event_date' => $this->event->date_debut->toISOString(),
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'timestamp' => now()->toISOString(),
            ],
            'priority' => 'high',
            'content_available' => true,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'events_channel', // Optionnel - canal dédié aux événements
                    'icon' => 'ic_notification',
                    'color' => '#FF6B35', // Couleur pour la notification
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'content-available' => 1,
                        'sound' => 'default',
                        'badge' => 1,
                        'alert' => [
                            'title' => $title,
                            'body' => $body,
                        ],
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
                Log::info('✅ Notification FCM nouvel événement envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'evenement_id' => $this->event->id,
                    'paroisse' => $paroisseName,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                    'message_id' => $responseData['message_id'] ?? null,
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM nouvel événement', [
                    'user_id' => $notifiable->id,
                    'evenement_id' => $this->event->id,
                    'paroisse' => $paroisseName,
                    'response' => $responseData,
                    'error' => $responseData['results'][0]['error'] ?? 'Unknown error',
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM nouvel événement', [
                'user_id' => $notifiable->id,
                'evenement_id' => $this->event->id,
                'paroisse' => $paroisseName,
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