<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Http;

class FcmHttpChannel
{
    public function send($notifiable, $notification)
    {
        if (!$notifiable->fcm_token) {
            return;
        }

        if (!method_exists($notification, 'toFcmHttp')) {
            return;
        }

        $message = $notification->toFcmHttp($notifiable);

        $serverKey = env('FIREBASE_SERVER_KEY');

        Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $message);
    }
}
