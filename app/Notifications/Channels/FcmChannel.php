<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;

class FcmHttpChannel
{
    public function send($notifiable, Notification $notification)
    {
        // Vérifier si la notification a la méthode toFcmHttp
        if (!method_exists($notification, 'toFcmHttp')) {
            throw new \Exception('Notification does not have toFcmHttp method');
        }

        // Appeler la méthode toFcmHttp de la notification
        return $notification->toFcmHttp($notifiable);
    }
}
