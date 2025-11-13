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
        // Récupérer le nom de la paroisse via la relation
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        return [
            'title' => 'Nouvel événement créé 🎉',
            'body' => "« {$paroisseName} que vous suivez organise un événement : {$this->event->titre} » qui aura lieu le " 
                      . $this->event->date_debut->format('d/m/Y') 
                      . ". Venez nombreux !",
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

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        $serverKey = env('FIREBASE_SERVER_KEY');

        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => 'Nouvel événement paroissial 🎊',
                'body' => "« {$paroisseName} » organise l'événement « {$this->event->titre} ».",
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
