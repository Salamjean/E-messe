<?php

// app/Notifications/TestNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Brozot\LaravelFcm\FcmMessage;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        // Pas besoin d'arguments pour un test simple
    }

    public function via($notifiable)
    {
        // Envoi via Firebase (fcm) et sauvegarde dans la base de données de Laravel
        return ['fcm', 'database'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => '🔔 Test Réussi !',
                'body' => 'Ceci est une notification de test réussie !',
            ])
            ->data([
                'type' => 'test_notif',
                'timestamp' => now()->timestamp
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Test Réussi !',
            'body' => 'Ceci est une notification de test réussie !',
            'type' => 'test_notif',
        ];
    }
}