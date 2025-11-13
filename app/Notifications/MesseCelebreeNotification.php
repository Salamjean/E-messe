<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseCelebreeNotification extends Notification
{
    use Queueable;

    protected $messe;

    /**
     * Crée une nouvelle notification.
     */
    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Définir les canaux de notification.
     */
    public function via($notifiable)
    {
        // Notification en base + FCM via HTTP
        return ['database', 'fcm_http'];
    }

    /**
     * Représentation pour la base de données.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Messe célébrée',
            'body' => "Votre demande de messe pour '{$this->messe->motif_intention}' a été célébrée.",
            'messe_id' => $this->messe->id,
        ];
    }

    /**
     * Notification FCM via HTTP.
     */
    public function toFcmHttp($notifiable)
    {
        if (!$notifiable->fcm_token) {
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => 'Messe célébrée',
                'body' => "Votre demande de messe pour '{$this->messe->motif_intention}' a été célébrée.",
            ],
            'data' => [
                'type' => 'messe_celebree',
                'messe_id' => $this->messe->id,
            ],
        ]);

        return $response->json();
    }
}
