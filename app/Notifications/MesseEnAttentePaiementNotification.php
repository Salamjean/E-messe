<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseEnAttentePaiementNotification extends Notification
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
     * Définir les canaux de notification.
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Représentation en base de données.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Messe en attente de paiement',
            'body' => "Votre demande de messe pour '{$this->messe->motif_intention}' est en attente de paiement.",
            'messe_id' => $this->messe->id,
            'type' => 'messe_en_attente_paiement',
        ];
    }

    /**
     * Notification FCM.
     */
    public function toFcm($notifiable)
    {
        if (!$notifiable->fcm_token) {
            Log::warning('Aucun token FCM pour l\'utilisateur', ['user_id' => $notifiable->id]);
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');
        
        if (!$serverKey) {
            Log::error('Clé FIREBASE_SERVER_KEY manquante dans .env');
            return null;
        }

        // Structure recommandée pour Flutter
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => 'Messe en attente de paiement',
                'body' => "Votre demande de messe pour '{$this->messe->motif_intention}' est en attente de paiement.",
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
            'data' => [
                'type' => 'messe_en_attente_paiement',
                'messe_id' => (string) $this->messe->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
            'priority' => 'high',
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(10)
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

            $responseData = $response->json();

            Log::info('Notification FCM envoyée', [
                'user_id' => $notifiable->id,
                'token' => substr($notifiable->fcm_token, 0, 10) . '...', // Log partiel pour sécurité
                'response' => $responseData,
                'success' => $response->successful(),
            ]);

            return $responseData;

        } catch (\Exception $e) {
            Log::error('Erreur envoi FCM', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}