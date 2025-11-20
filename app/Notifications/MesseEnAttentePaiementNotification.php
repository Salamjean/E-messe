<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseEnAttentePaiementNotification extends Notification
{
    use Queueable;

    protected Messe $messe;

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
        return ['database', 'fcm_http'];
    }

    /**
     * Représentation en base de données.
     */
    public function toArray($notifiable)
    {
        return [
            'type'     => 'messe_en_attente_paiement',
            'title'    => 'Messe en attente de paiement',
            'body'     => "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.",
            'messe_id' => $this->messe->id,
        ];
    }

    /**
     * Notification envoyée via FCM HTTP.
     */
    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $title = 'Messe en attente de paiement';
        $body  = "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.";

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

        // Log d’erreur si FCM échoue
        if ($response->failed()) {
            Log::error('FCM Error - MesseEnAttentePaiementNotification', [
                'response' => $response->body(),
                'payload'  => $payload,
            ]);
        }

        return $response->json();
    }
}
