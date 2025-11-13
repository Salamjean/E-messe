<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Paiement;

class PaiementEchecNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $title = 'Échec du Paiement';
        $body = "Votre paiement de {$this->paiement->montant} {$this->paiement->devise} a échoué. Veuillez réessayer.";

        if (!empty($notifiable->fcm_token)) {
            try {
                Http::withHeaders([
                    'Authorization' => 'key=' . env('FIREBASE_SERVER_KEY'),
                    'Content-Type' => 'application/json',
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $notifiable->fcm_token,
                    'notification' => compact('title', 'body'),
                    'data' => [
                        'type' => 'paiement_echec',
                        'paiement_id' => $this->paiement->id,
                        'messe_id' => $this->paiement->messe_id,
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Échec envoi FCM (paiement #{$this->paiement->id}) : " . $e->getMessage());
            }
        }

        return [
            'type' => 'paiement_echec',
            'title' => $title,
            'body' => $body,
            'paiement_id' => $this->paiement->id,
        ];
    }
}
