<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Messe;

class MesseConfirmeeNotification extends Notification
{
    use Queueable;

    protected $messe;

    /**
     * Crée une nouvelle notification
     */
    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Canaux de notification
     */
    public function via($notifiable)
    {
        return ['database']; // database seulement, FCM sera envoyé dans toDatabase()
    }

    /**
     * Données à stocker en base + envoi FCM
     */
    public function toDatabase($notifiable)
    {
        $motif = $this->messe->motif_intention ?? 'votre intention';
        $messageBody = "Bonne nouvelle ! Votre demande de messe pour \"$motif\" a été confirmée.";

        // 🔹 Envoi FCM
        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    'notification' => [
                        'title' => 'Messe Confirmée',
                        'body' => $messageBody,
                    ],
                    'data' => [
                        'type' => 'messe_confirmee',
                        'messe_id' => $this->messe->id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM pour la messe #{$this->messe->id}: ".$e->getMessage());
            }
        }

        // 🔹 Retour pour enregistrement dans `notifications.data`
        return [
            'type' => 'messe_confirmee',
            'title' => 'Messe Confirmée',
            'body' => $messageBody,
            'messe_id' => $this->messe->id,
        ];
    }
}
