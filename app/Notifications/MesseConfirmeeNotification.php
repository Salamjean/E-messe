<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;
use App\Notifications\Channels\FcmHttpChannel;

class MesseConfirmeeNotification extends Notification
{
    use Queueable;

    protected Messe $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

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

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $motif = $this->messe->motif_intention ?? 'votre intention';
        $title = 'Messe confirmée';
        $body  = "Bonne nouvelle ! Votre demande de messe pour « {$motif} » a été confirmée.";
        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'messe_confirmee',
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

        if ($response->failed()) {
            Log::error('FCM Error - MesseConfirmee', ['response' => $response->body()]);
        }

        return $response->json();
    }
}