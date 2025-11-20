<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseAnnuleeNotification extends Notification
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
        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';

        return [
            'type'      => 'messe_annulee',
            'title'     => 'Messe annulée',
            'body'      => "Votre demande de messe à {$paroisse} a été annulée.",
            'messe_id'  => $this->messe->id,
        ];
    }

    /**
     * Envoi FCM via HTTP + retour JSON
     */
    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';

        $title = 'Messe annulée';
        $body  = "Votre demande de messe à {$paroisse} a été annulée.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,

            // --- 1. Bloc notification visuel ---
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],

            // --- 2. Bloc DATA : utile pour Flutter ---
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'messe_annulee',
                'messe_id'     => (string) $this->messe->id,
                'title'        => $title,
                'body'         => $body,
            ],

            'priority' => 'high',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        // Log si erreur FCM
        if ($response->failed()) {
            Log::error('FCM Error - MesseAnnuleeNotification', [
                'response' => $response->body(),
                'payload'  => $payload,
            ]);
        }

        return $response->json();
    }
}
