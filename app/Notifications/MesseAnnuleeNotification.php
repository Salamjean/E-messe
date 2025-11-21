<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;
// IMPORTANT : Import du canal personnalisé
use App\Notifications\Channels\FcmHttpChannel;

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected Messe $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * CORRECTION ICI : On appelle la classe du canal directement
     */
    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

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

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';
        $title = 'Messe annulée';
        $body  = "Votre demande de messe à {$paroisse} a été annulée.";

        return $this->sendToFirebase($notifiable->fcm_token, $title, $body, [
            'type'     => 'messe_annulee',
            'messe_id' => (string) $this->messe->id,
        ]);
    }

    /**
     * Helper privé pour éviter de répéter le code HTTP
     */
    private function sendToFirebase($token, $title, $body, $data = [])
    {
        $serverKey = env('FIREBASE_SERVER_KEY');
        
        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => array_merge([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title'        => $title,
                'body'         => $body,
            ], $data),
            'priority' => 'high',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        if ($response->failed()) {
            Log::error('FCM Error', ['response' => $response->body()]);
        }

        return $response->json();
    }
}