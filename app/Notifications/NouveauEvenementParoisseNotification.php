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

    public function via($notifiable)
    {
        return ['database', 'fcm']; // database + push mobile
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nouvel événement créé 🎉',
            'body' => "« {$this->event->titre} » aura lieu le " . $this->event->date_debut->format('d/m/Y'),
            'evenement_id' => $this->event->id,
            'paroisse_id' => $this->event->created_by,
        ];
    }

    public function toFcm($notifiable)
    {
        if (!$notifiable->fcm_token) {
            return;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
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

        Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);
    }
}
