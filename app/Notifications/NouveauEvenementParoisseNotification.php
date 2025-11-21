<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService;

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    // ✅ On précise ici qu'on veut utiliser la Base de données ET notre canal personnalisé
    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

    // Pour la base de données interne
    public function toArray($notifiable)
    {
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        return [
            'type'          => 'nouveau_evenement',
            'title'         => 'Nouvel événement paroissial 🎊',
            'body'          => "{$paroisseName} organise : « {$this->event->titre} ».",
            'evenement_id'  => $this->event->id,
            'paroisse_id'   => $this->event->created_by,
        ];
    }

    // ✅ Cette méthode est appelée automatiquement par FcmHttpChannel
    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $paroisseName  = $this->event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $this->event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body  = "{$paroisseName} organise l'événement « {$this->event->titre} » le {$dateFormatted}.";

        // On appelle le service pour envoyer via l'API V1
        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action'  => 'FLUTTER_NOTIFICATION_CLICK',
            'type'          => 'nouveau_evenement',
            'evenement_id'  => (string) $this->event->id,
            'paroisse_id'   => (string) $this->event->created_by,
            'paroisse_name' => $paroisseName,
            'title'         => $title,
            'body'          => "Ceci est le body data invisible" 
        ]);
    }
}