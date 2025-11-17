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

    /**
     * Constructeur.
     */
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
        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';

        return [
            'type'      => 'messe_annulee',
            'title'     => 'Messe annulée',
            'body'      => "Votre demande de messe à {$paroisse} a été annulée.",
            'messe_id'  => $this->messe->id,
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

        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';

        $title = 'Messe annulée';
        $body  = "Votre demande de messe à {$paroisse} a été annulée.";

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            'data' => [
                'title'     => $title,
                'body'      => $body,
                'type'      => 'messe_annulee',
                'messe_id'  => $this->messe->id,
            ],
        ]);

        return $response->json();
    }
}
