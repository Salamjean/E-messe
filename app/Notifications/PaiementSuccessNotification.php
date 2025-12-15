<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Paiement;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService;

class PaiementSuccessNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['database', FcmHttpChannel::class];
    }

    public function toArray($notifiable)
    {
        $montant = $this->paiement->montant ?? 'inconnu';
        $devise  = $this->paiement->devise  ?? '';

        return [
            'type'        => 'paiement_reussi',
            'title'       => 'Paiement Réussi',
            'body'        => "Votre paiement de {$montant} {$devise} a réussi.",
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $montant = $this->paiement->montant ?? 'inconnu';
        $devise  = $this->paiement->devise  ?? '';

        $title = 'Paiement Réussi';
        $body  = "Votre paiement de {$montant} {$devise} a réussi.";

        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type'         => 'paiement_reussi',
            'paiement_id'  => (string) $this->paiement->id,
        ]);
    }
}