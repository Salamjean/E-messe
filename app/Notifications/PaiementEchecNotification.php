<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Paiement;

class PaiementEchecNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
    }

    /**
     * Canaux utilisés
     */
    public function via($notifiable)
    {
        return ['database', 'fcm_http'];
    }

    /**
     * Données enregistrées en base
     */
    public function toArray($notifiable)
    {
        $montant = $this->paiement->montant ?? 'montant inconnu';
        $devise  = $this->paiement->devise  ?? '';

        return [
            'type'        => 'paiement_echec',
            'title'       => 'Échec du Paiement',
            'body'        => "Votre paiement de {$montant} {$devise} a échoué. Veuillez réessayer.",
            'paiement_id' => $this->paiement->id,
        ];
    }

    /**
     * Notification FCM via HTTP
     */
    public function toFcmHttp($notifiable)
    {
        if (!$notifiable->fcm_token) {
            Log::warning('❌ Aucun token FCM pour cet utilisateur', [
                'user_id' => $notifiable->id
            ]);
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');
        if (!$serverKey) {
            Log::error('❌ Clé FIREBASE_SERVER_KEY manquante');
            return null;
        }

        $montant = $this->paiement->montant ?? 'montant inconnu';
        $devise  = $this->paiement->devise  ?? '';

        $title = 'Échec du Paiement';
        $body  = "Votre paiement de {$montant} {$devise} a échoué. Veuillez réessayer.";

        $payload = [
            'to' => $notifiable->fcm_token,

            // --- 1. Bloc affichage ---
            'notification' => [
                'title' => $title,
                'body'  => $body,
                'sound' => 'default',
            ],

            // --- 2. Bloc DATA : utile pour Flutter ---
            'data' => [
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'type'         => 'paiement_echec',
                'paiement_id'  => (string) $this->paiement->id,
                'title'        => $title,
                'body'         => $body,
            ],

            'priority' => 'high',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->timeout(10)->post('https://fcm.googleapis.com/fcm/send', $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                Log::info('✅ Notification FCM Paiement Échec envoyée avec succès', [
                    'user_id'     => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'response'    => $responseData,
                ]);
            } else {
                Log::warning('❌ FCM a répondu avec une erreur', [
                    'user_id'     => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'response'    => $responseData,
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Exception lors de l’envoi FCM Paiement Échec', [
                'user_id'     => $notifiable->id,
                'paiement_id' => $this->paiement->id,
                'error'       => $e->getMessage()
            ]);

            return null;
        }
    }
}
