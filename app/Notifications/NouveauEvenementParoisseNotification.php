<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Brozot\LaravelFcm\FcmMessage;
use App\Models\Messe; // On considère une messe comme un "événement"

class NouveauEvenementParoisseNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        return ['fcm', 'database'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => 'Nouvel événement dans une de vos paroisses favorites',
                'body' => 'La paroisse "' . $this->messe->paroisse->name . '" a programmé une nouvelle messe.',
            ])
            ->data([
                'type' => 'nouvel_evenement',
                'paroisse_id' => $this->messe->paroisse_id,
                'messe_id' => $this->messe->id
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouvel événement',
            'body' => 'La paroisse "' . $this->messe->paroisse->name . '" a programmé une nouvelle messe.',
            'paroisse_id' => $this->messe->paroisse_id,
        ];
    }
}