<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use App\Models\Paiement;

class PaiementSuccessNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    public function via($notifiable)
    {
        return ['database', 'fcm_http'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Paiement Réussi',
            'body' => 'Votre paiement de ' . $this->paiement->montant . ' ' . $this->paiement->devise . ' a été effectué avec succès.',
            'paiement_id' => $this->paiement->id,
        ];
    }

    public function toFcmHttp($notifiable)
    {
        if (!$notifiable->fcm_token) return null;

        $serverKey = env('FIREBASE_SERVER_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $notifiable->fcm_token,
            // 'notification' => [

            // ],
            'data' => [
                'title' => 'Paiement Réussi',
                'body' => 'Votre paiement de ' . $this->paiement->montant . ' ' . $this->paiement->devise . ' a été effectué avec succès.',
                'type' => 'paiement_reussi',
                'paiement_id' => $this->paiement->id,
                'messe_id' => $this->paiement->messe_id,
            ],
        ]);

        return $response->json();
    }
}
