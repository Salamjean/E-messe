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
        return [
            'type'     => 'messe_celebree',
            'title'    => 'Messe célébrée',
            'body'     => "Votre demande de messe pour « {$this->messe->motif_intention} » a été célébrée.",
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

        $title = 'Messe célébrée';
        $body  = "Votre demande de messe pour « {$this->messe->motif_intention} » a été célébrée.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            'data' => [
                'title'     => $title,
                'body'      => $body,
                'type'      => 'messe_celebree',
                'messe_id'  => $this->messe->id,
            ],
        ]);

        return $response->json();
    }
}
