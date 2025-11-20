<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Canaux de notification : database + FCM HTTP
     */
    public function via($notifiable)
    {
        return ['database', 'fcm_http'];
    }

    /**
     * Données enregistrées en base
     */
    public function toArray($notifiable)
    {
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        return [
            'type'          => 'nouveau_evenement',
            'title'         => 'Nouvel événement paroissial 🎊',
            'body'          => "{$paroisseName} que vous suivez organise un événement : « {$this->event->titre} », prévu le " .
                               $this->event->date_debut->format('d/m/Y') . ".",
            'evenement_id'  => $this->event->id,
            'paroisse_id'   => $this->event->created_by, // ✔ cohérent avec ton modèle
        ];
    }



    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            return null;
        }

        $paroisseName  = $this->event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $this->event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body  = "{$paroisseName} organise l'événement « {$this->event->titre} » le {$dateFormatted}.";

        return [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default'
            ],
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'nouveau_evenement',
                'evenement_id' => (string) $this->event->id,
                'paroisse_id'  => (string) $this->event->created_by,
                'paroisse_name'=> $paroisseName,
                'title'        => $title,
                'body'         => $body,
            ],
            'priority' => 'high',
        ];
    }
}
