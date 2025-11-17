<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Canaux de notification
     */
    public function via($notifiable)
    {
        // Database pour stocker et FCM envoyé manuellement
        return ['database'];
    }

    /**
     * Données enregistrées dans la base de données
     */
    public function toArray($notifiable)
    {
        // Envoi FCM manuel
        $this->sendFcm($notifiable);

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        $title = 'Nouvel événement créé 🎉';
        $body = "{$paroisseName} que vous suivez organise un événement : « {$this->event->titre} », " .
                "prévu le " . $this->event->date_debut->format('d/m/Y') . ".";

        return [
            'type'          => 'nouveau_evenement',
            'title'         => $title,
            'body'          => $body,
            'evenement_id'  => $this->event->id,
            'paroisse_id'   => $this->event->created_by,
        ];
    }

    /**
     * Méthode pour envoyer la notification FCM via HTTP
     */
    protected function sendFcm($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return;
        }

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        $title = 'Nouvel événement paroissial 🎊';
        $body = "{$paroisseName} organise l'événement « {$this->event->titre} ».";

        $serverKey = env('FIREBASE_SERVER_KEY');

        Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            'data' => [
                'title'        => $title,
                'body'         => $body,
                'type'         => 'nouveau_evenement',
                'evenement_id' => $this->event->id,
                'paroisse_id'  => $this->event->created_by,
            ],
            'priority' => 'high',
        ]);
    }
}
