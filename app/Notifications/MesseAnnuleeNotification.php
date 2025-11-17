<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Messe;

class MesseAnnuleeNotification extends Notification
{
    use Queueable;

    protected $messe;

    /**
     * Constructeur.
     */
    public function __construct(Messe $messe)
    {
        $this->messe = $messe;
    }

    /**
     * Canaux : database + FCM
     */
    public function via($notifiable)
    {
        return ['database', 'fcm'];
    }

    /**
     * Données enregistrées dans la base
     */
    public function toArray($notifiable)
    {
        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';

        return [
            'type'      => 'messe_annulee',
            'title'     => 'Messe annulée',
            'body'      => "Votre demande de messe à {$paroisse} a été annulée.",
            'messe_id'  => $this->messe->id,
        ];
    }

    /**
     * Notification FCM corrigée
     */
    public function toFcm($notifiable)
    {
        if (empty($notifiable->fcm_token)) {
            Log::warning('Aucun token FCM pour l\'utilisateur', ['user_id' => $notifiable->id]);
            return null;
        }

        $serverKey = env('FIREBASE_SERVER_KEY');
        
        if (!$serverKey) {
            Log::error('Clé FIREBASE_SERVER_KEY manquante dans .env');
            return null;
        }

        $paroisse = $this->messe->paroisse->name ?? 'la paroisse';
        $title = 'Messe annulée';
        $body = "Votre demande de messe à {$paroisse} a été annulée.";

        // Structure FCM corrigée
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
            'data' => [
                'title' => $title,     // Doublon pour compatibilité
                'body' => $body,       // Doublon pour compatibilité
                'type' => 'messe_annulee',
                'messe_id' => (string) $this->messe->id,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
            'priority' => 'high',
            'content_available' => true,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])
            ->timeout(10)
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

            $responseData = $response->json();

            // Log du résultat
            if ($response->successful() && isset($responseData['success']) && $responseData['success'] == 1) {
                Log::info('✅ Notification FCM annulation envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'messe_id' => $this->messe->id,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM annulation', [
                    'user_id' => $notifiable->id,
                    'response' => $responseData,
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM annulation', [
                'user_id' => $notifiable->id,
                'messe_id' => $this->messe->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Route FCM (optionnel mais recommandé)
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->fcm_token;
    }
}