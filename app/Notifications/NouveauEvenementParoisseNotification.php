<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

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
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = 'Nouvel événement';
        $body = 'La paroisse "' . $this->messe->paroisse->name . '" a programmé une nouvelle messe.';

        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    'notification' => compact('title', 'body'),
                    'data' => [
                        'type' => 'nouvel_evenement',
                        'paroisse_id' => $this->messe->paroisse_id,
                        'messe_id' => $this->messe->id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM (messe #{$this->messe->id}) : " . $e->getMessage());
            }
        }

        return [
            'type' => 'nouvel_evenement',
            'title' => $title,
            'body' => $body,
            'paroisse_id' => $this->messe->paroisse_id,
            'messe_id' => $this->messe->id,
        ];
    }
}
