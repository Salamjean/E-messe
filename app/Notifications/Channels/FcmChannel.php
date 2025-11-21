<?php

namespace App\Notifications\Channels;
use App\Notifications\Channels\FcmHttpChannel; 

use Illuminate\Notifications\Notification;

class FcmHttpChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notification, 'toFcmHttp')) {
            return $notification->toFcmHttp($notifiable);
        }
        return null;
    }
}