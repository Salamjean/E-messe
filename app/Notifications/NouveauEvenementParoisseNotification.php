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
     * Canaux de notification
     */
    public function via($notifiable)
    {
        // ❌ On supprime 'fcm_http'
        return ['database'];
    }

    /**
     * Données enregistrées dans la base de données
     */
    public function toArray($notifiable)
    {
        // Envoi manuel FCM
        $this->sendFcm($notifiable);

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';
        $title = 'Nouvel événement créé 🎉';
        $body = "{$paroisseName} que vous suivez organise un événement : « {$this->event->titre} », " .
                "prévu le " . $this->event->date_debut->format('d/m/Y') . ".";

        return [
            'type'          => 'nouveau_evenement',
            'title'         => $title,
            'body'          => $body,
            'evenement_id'  => $this->event->id,
            'paroisse_id'   => $this->event->created_by,
        ];
    }

    /**
     * Envoi manuel FCM
     */
    protected function sendFcm($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            Log::info('Aucun token FCM pour l\'utilisateur', ['user_id' => $notifiable->id]);
            return;
        }

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $this->event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body = "{$paroisseName} organise l'événement « {$this->event->titre} » le {$dateFormatted}.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        if (!$serverKey) {
            Log::error('Clé FIREBASE_SERVER_KEY manquante');
            return;
        }

        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'icon' => 'ic_notification',
                'badge' => '1'
            ],
            'data' => [
                'title' => $title,
                'body' => $body,
                'type' => 'nouveau_evenement',
                'evenement_id' => (string) $this->event->id,
                'paroisse_id' => (string) $this->event->created_by,
                'paroisse_name' => $paroisseName,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
            ],
            'priority' => 'high'
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->timeout(10)->post('https://fcm.googleapis.com/fcm/send', $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['success']) && $responseData['success'] == 1) {
                Log::info('✅ Notification FCM envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'event_id' => $this->event->id,
                    'message_id' => $responseData['message_id'] ?? null
                ]);
            } else {
                Log::warning('❌ Échec envoi FCM', [
                    'user_id' => $notifiable->id,
                    'response' => $responseData
                ]);
            }
        } catch (\Exception $e) {
            Log::error('💥 Erreur FCM', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
