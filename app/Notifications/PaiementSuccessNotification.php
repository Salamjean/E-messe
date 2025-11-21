<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Paiement;
use App\Notifications\Channels\FcmHttpChannel;

class PaiementSuccessNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

    public function toArray($notifiable)
    {
        $montant = $this->paiement->montant ?? 'montant inconnu';
        $devise  = $this->paiement->devise  ?? '';

        return [
            'type'        => 'paiement_reussi',
            'title'       => 'Paiement Réussi',
            'body'        => "Votre paiement de {$montant} {$devise} a été effectué avec succès.",
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (!$notifiable->fcm_token) return null;

        $serverKey = env('FIREBASE_SERVER_KEY');
        $montant = $this->paiement->montant ?? 'inconnu';
        $devise  = $this->paiement->devise  ?? '';

        $title = 'Paiement Réussi';
        $body  = "Votre paiement de {$montant} {$devise} a été effectué avec succès.";

        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'paiement_reussi',
                'paiement_id'  => (string) $this->paiement->id,
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
            Log::error('FCM Error - PaiementSuccess', ['response' => $response->body()]);
        }

        return $response->json();
    }
}