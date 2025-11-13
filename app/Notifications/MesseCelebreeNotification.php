<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseCelebreeNotification extends Notification
{
    use Queueable;

    protected $messe;

    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = 'Messe célébrée';
        $body = "Votre demande de messe pour \"{$this->messe->motif_intention}\" a été célébrée.";

        // 🔹 Envoi FCM
        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    'notification' => compact('title', 'body'),
                    'data' => [
                        'type' => 'messe_celebree',
                        'messe_id' => $this->messe->id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM (messe #{$this->messe->id}) : " . $e->getMessage());
            }
        }

        return [
            'type' => 'messe_celebree',
            'title' => $title,
            'body' => $body,
            'messe_id' => $this->messe->id,
        ];
    }
}
