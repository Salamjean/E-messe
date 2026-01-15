<?php

namespace App\Notifications;

use App\Models\Event;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification; // Import correct

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

    public function toArray($notifiable)
    {
        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';

        return [
            'type' => 'nouveau_evenement',
            'title' => 'Nouvel événement paroissial 🎊',
            'body' => "{$paroisseName} organise : « {$this->event->titre} ».",
            'evenement_id' => $this->event->id,
            'paroisse_id' => $this->event->created_by,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            \Log::info('FCM token manquant pour', ['user_id' => $notifiable->id]);

            return null;
        }

        $paroisseName = $this->event->paroisse->name ?? 'la paroisse';
        $dateFormatted = $this->event->date_debut->format('d/m/Y');

        $title = 'Nouvel événement paroissial 🎊';
        $body = "{$paroisseName} organise l'événement « {$this->event->titre} » le {$dateFormatted}.";

        return (new FcmService)->send(
            $notifiable->fcm_token,
            $title,
            $body,
            [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type' => 'nouveau_evenement',
                'evenement_id' => (string) $this->event->id,
                'paroisse_id' => (string) $this->event->created_by,
                'paroisse_name' => $paroisseName,
            ],
            $notifiable->id // ← Ajouter l'user ID pour le nettoyage
        );
    }
}
