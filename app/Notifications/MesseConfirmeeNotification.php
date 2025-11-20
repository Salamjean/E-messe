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

    protected Messe $messe;

    /**
     * Constructeur
     */
    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Canaux : database + FCM HTTP
     */
    public function via($notifiable)
    {
        return ['database', 'fcm_http'];
    }

    /**
     * Données enregistrées en base
     */
    public function toArray($notifiable)
    {
        $motif = $this->messe->motif_intention ?? 'votre intention';

        return [
            'type'      => 'messe_confirmee',
            'title'     => 'Messe confirmée',
            'body'      => "Bonne nouvelle ! Votre demande de messe pour « {$motif} » a été confirmée.",
            'messe_id'  => $this->messe->id,
        ];
    }

    /**
     * Envoi FCM via HTTP (corrigé et propre)
     */
    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $motif = $this->messe->motif_intention ?? 'votre intention';

        $title = 'Messe confirmée';
        $body  = "Bonne nouvelle ! Votre demande de messe pour « {$motif} » a été confirmée.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,

            // --- 1. Notification visuelle ---
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],

            // --- 2. Data pour Flutter ---
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'messe_confirmee',
                'messe_id'     => (string) $this->messe->id,
                'title'        => $title,
                'body'         => $body,
            ],

            'priority' => 'high',
        ];

        // Envoi vers FCM
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        // Log si erreur FCM
        if ($response->failed()) {
            Log::error('FCM Error - MesseConfirmeeNotification', [
                'response' => $response->body(),
                'payload'  => $payload,
            ]);
        }

        return $response->json();
    }
}
