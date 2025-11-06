<?php

namespace App\Notifications\Channels;

use Illuminate\Support\Facades\Http;

class FcmChannel
{
    public function send($notifiable, $notification)
    {
        if (!method_exists($notification, 'toFcm')) {
            return;
        }

        $fcmMessage = $notification->toFcm($notifiable);

        if (!$notifiable->fcm_token) {
            return;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';

        Http::withHeaders([
            'Authorization' => 'key=' . env('FCM_SERVER_KEY'),
            'Content-Type' => 'application/json',
        ])->post($url, $fcmMessage);
    }
}
