<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class FcmHttpChannel
{
    /**
     * Envoi de la notification via ce canal.
     */
    public function send($notifiable, Notification $notification)
    {
        // Vérifie si la méthode toFcmHttp existe dans la classe de Notification
        if (!method_exists($notification, 'toFcmHttp')) {
            return null;
        }

        // Appelle la méthode et retourne le résultat
        return $notification->toFcmHttp($notifiable);
    }
}