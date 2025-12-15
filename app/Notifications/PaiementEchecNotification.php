<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Paiement;
use App\Notifications\Channels\FcmHttpChannel;
use App\Services\FcmService;

class PaiementEchecNotification extends Notification
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
            'type'        => 'paiement_echec',
            'title'       => 'Échec du Paiement',
            'body'        => "Votre paiement de {$montant} {$devise} a échoué.",
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (empty($notifiable->fcm_token)) return null;

        $montant = $this->paiement->montant ?? 'inconnu';
        $devise  = $this->paiement->devise  ?? '';
        
        $title = 'Échec du Paiement';
        $body  = "Votre paiement de {$montant} {$devise} a échoué.";

        return (new FcmService())->send($notifiable->fcm_token, $title, $body, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'type'         => 'paiement_echec',
            'paiement_id'  => (string) $this->paiement->id,
        ]);
    }
}