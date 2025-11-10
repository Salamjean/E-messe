<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Brozot\LaravelFcm\FcmMessage;
use App\Models\Paiement;

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
        return ['fcm', 'database'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => 'Échec du Paiement',
                'body' => 'Votre paiement de ' . $this->paiement->montant . ' ' . $this->paiement->devise . ' a échoué. Veuillez réessayer.',
            ])
            ->data([
                'type' => 'paiement_echec',
                'paiement_id' => $this->paiement->id,
                'messe_id' => $this->paiement->messe_id
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Échec du Paiement',
            'body' => 'Votre paiement de ' . $this->paiement->montant . ' ' . $this->paiement->devise . ' a échoué. Veuillez réessayer.',
            'paiement_id' => $this->paiement->id,
        ];
    }
}