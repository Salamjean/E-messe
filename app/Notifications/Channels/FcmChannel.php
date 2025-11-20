<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmHttpChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toFcmHttp')) {
            return;
        }

        $payload = $notification->toFcmHttp($notifiable);

        if (!$payload) return;

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        if ($response->failed()) {
            Log::error('FCM Error - Custom Channel', [
                'response' => $response->body(),
                'payload'  => $payload,
            ]);
        }
    }
}
