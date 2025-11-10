<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Brozot\LaravelFcm\FcmMessage;
use App\Models\Messe;

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Spécifie les canaux de notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Envoi via Firebase (fcm) et sauvegarde dans la base de données de Laravel
        return ['fcm', 'database'];
    }

    /**
     * Définit le message pour Firebase Cloud Messaging.
     *
     * @param  mixed  $notifiable
     * @return \Brozot\LaravelFcm\FcmMessage
     */
    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => 'Demande de messe annulée',
                'body' => "Votre demande de messe à la paroisse {$this->messe->paroisse->name} a été annulée.",
            ])
            ->data([
                'type' => 'messe_annulee',
                'messe_id' => (string)$this->messe->id
            ]);
    }

    /**
     * Définit la représentation en tableau (pour la base de données).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Demande de messe annulée',
            'body' => "Votre demande de messe à la paroisse {$this->messe->paroisse->name} a été annulée.",
            'messe_id' => $this->messe->id,
        ];
    }
}