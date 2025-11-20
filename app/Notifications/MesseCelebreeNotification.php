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

    protected Messe $messe;

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
     * Données enregistrées dans la base
     */
    public function toArray($notifiable)
    {
        $motif = $this->messe->motif_intention ?? 'votre intention';

        return [
            'type'     => 'messe_celebree',
            'title'    => 'Messe célébrée',
            'body'     => "Votre demande de messe pour « {$motif} » a été célébrée.",
            'messe_id' => $this->messe->id,
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

        $motif = $this->messe->motif_intention ?? 'votre intention';

        $title = 'Messe célébrée';
        $body  = "Votre demande de messe pour « {$motif} » a été célébrée.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,

            // --- 1. Bloc obligatoire pour l'affichage visuel ---
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],

            // --- 2. Bloc DATA : utile pour Flutter ---
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'messe_celebree',
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

        // Log si erreur
        if ($response->failed()) {
            Log::error('FCM Error - MesseCelebreeNotification', [
                'response' => $response->body(),
                'payload'  => $payload,
            ]);
        }

        return $response->json();
    }
}
