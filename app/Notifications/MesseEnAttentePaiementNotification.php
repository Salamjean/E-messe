<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;
use App\Notifications\Channels\FcmHttpChannel;

class MesseEnAttentePaiementNotification extends Notification
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
        return [
            'type'     => 'messe_en_attente_paiement',
            'title'    => 'Messe en attente de paiement',
            'body'     => "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.",
            'messe_id' => $this->messe->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $title = 'Messe en attente de paiement';
        $body  = "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.";
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
                'type'         => 'messe_en_attente_paiement',
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
            Log::error('FCM Error - AttentePaiement', ['response' => $response->body()]);
        }

        return $response->json();
    }
}