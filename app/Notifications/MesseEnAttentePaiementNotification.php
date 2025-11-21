<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Messe;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService;

class MesseEnAttentePaiementNotification extends Notification
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
        return [
            'type'     => 'messe_en_attente_paiement',
            'title'    => 'Messe en attente de paiement',
            'body'     => "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.",
            'messe_id' => $this->messe->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $title = 'Messe en attente de paiement';
        $body  = "Votre demande de messe pour « {$this->messe->motif_intention} » est en attente de paiement.";

        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type'         => 'messe_en_attente_paiement',
            'messe_id'     => (string) $this->messe->id,
        ]);
    }
}