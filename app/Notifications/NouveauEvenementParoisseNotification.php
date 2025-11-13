<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class NouveauEvenementParoisseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Canaux de notification : base de données + push FCM
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Contenu pour la table notifications
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouvel événement créé 🎉',
            'body' => "« {$this->event->titre} » aura lieu le " . $this->event->date_debut->format('d/m/Y'),
            'evenement_id' => $this->event->id,
            'paroisse_id' => $this->event->created_by,
        ];
    }

    /**
     * Envoi FCM manuel
     */
    public function toFcm($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => 'Nouvel événement paroissial 🎊',
                'body' => "« {$this->event->titre} » organisé par votre paroisse.",
            ],
            'data' => [
                'evenement_id' => $this->event->id,
            ],
        ];

        return Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);
    }
}
