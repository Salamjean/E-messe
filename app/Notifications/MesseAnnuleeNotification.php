<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Messe;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService; // Import du service

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected Messe $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

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

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';
        $title = 'Messe annulée';
        $body  = "Votre demande de messe à {$paroisse} a été annulée.";

        // Appel au nouveau service V1
        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type'         => 'messe_annulee',
            'messe_id'     => (string) $this->messe->id,
        ]);
    }
}