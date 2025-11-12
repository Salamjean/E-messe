<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        return ['database', 'fcm_http'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Demande de messe annulée',
            'body' => "Votre demande de messe à la paroisse {$this->messe->paroisse->name} a été annulée.",
            'messe_id' => $this->messe->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (!$notifiable->fcm_token) return null;

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => 'Demande de messe annulée',
                'body' => "Votre demande de messe à la paroisse {$this->messe->paroisse->name} a été annulée.",
            ],
            'data' => [
                'type' => 'messe_annulee',
                'messe_id' => $this->messe->id,
            ],
        ]);

        return $response->json();
    }
}
