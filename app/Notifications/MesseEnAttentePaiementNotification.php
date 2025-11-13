<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseEnAttentePaiementNotification extends Notification
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
        $title = 'Messe en attente de paiement';
        $body = "Votre demande de messe pour \"{$this->messe->motif_intention}\" est en attente de paiement.";

        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    'notification' => compact('title', 'body'),
                    'data' => [
                        'type' => 'messe_en_attente_paiement',
                        'messe_id' => $this->messe->id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM (messe #{$this->messe->id}) : " . $e->getMessage());
            }
        }

        return [
            'type' => 'messe_en_attente_paiement',
            'title' => $title,
            'body' => $body,
            'messe_id' => $this->messe->id,
        ];
    }
}
