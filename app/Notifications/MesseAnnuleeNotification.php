<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected $messe;

    /**
     * Crée une nouvelle notification.
     */
    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Canal de notification.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Données enregistrées en base et envoi FCM.
     */
    public function toDatabase($notifiable)
    {
        $title = 'Demande de messe annulée';
        $body = "Votre demande de messe à la paroisse {$this->messe->paroisse->name} a été annulée.";

        // 🔹 Envoi FCM
        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    // 'notification' => compact('title', 'body'),
                    // 'data' => [
                    //     'type' => 'messe_annulee',
                    //     'messe_id' => $this->messe->id,
                    // ],

                    'data' => [
                        'title' => 'Demande annulee',
                        'body'  => "«Votre demande  a été annulée ». ",
                        'type' => 'messe_annulee',
                        'messe_id' => $this->messe->id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM pour la messe annulée #{$this->messe->id}: " . $e->getMessage());
            }
        }

        // 🔹 Enregistrement en base
        return [
            'type' => 'messe_annulee',
            'title' => $title,
            'body' => $body,
            'messe_id' => $this->messe->id,
        ];
    }
}
