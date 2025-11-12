<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseConfirmeeNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        // Notification en base + FCM via HTTP
        return ['database', 'fcm_http'];
    }

    /**
     * Pour la base de données
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Messe Confirmée',
            'body' => 'Bonne nouvelle ! Votre demande de messe pour "' . $this->messe->motif_intention . '" a été confirmée.',
            'messe_id' => $this->messe->id,
        ];
    }

    /**
     * Pour FCM via HTTP
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
                'title' => 'Messe Confirmée',
                'body' => 'Bonne nouvelle ! Votre demande de messe pour "' . $this->messe->motif_intention . '" a été confirmée.',
            ],
            'data' => [
                'type' => 'messe_confirmee',
                'messe_id' => $this->messe->id,
            ],
        ]);

        return $response->json();
    }
}
