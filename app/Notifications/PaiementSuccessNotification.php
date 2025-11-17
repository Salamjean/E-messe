<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Paiement;

class PaiementSuccessNotification extends Notification
{
    use Queueable;

    protected $paiement;

    public function __construct(Paiement $paiement)
    {
        $this->paiement = $paiement;
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
        $montantFormatted = number_format($this->paiement->montant, 2, ',', ' ');

        return [
            'type' => 'paiement_reussi',
            'title' => 'Paiement réussi ✅',
            'body' => 'Votre paiement de ' . $montantFormatted . ' ' . $this->paiement->devise . ' a été effectué avec succès.',
            'paiement_id' => $this->paiement->id,
            'messe_id' => $this->paiement->messe_id,
            'montant' => $this->paiement->montant,
            'devise' => $this->paiement->devise,
            'motif_intention' => $this->paiement->messe->motif_intention ?? 'Intention inconnue',
            'reference' => $this->paiement->reference ?? null,
        ];
    }

    /**
     * Notification FCM corrigée
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

        $montantFormatted = number_format($this->paiement->montant, 2, ',', ' ');
        $motifIntention = $this->paiement->messe->motif_intention ?? 'votre intention';

        $title = 'Paiement réussi ✅';
        $body = 'Votre paiement de ' . $montantFormatted . ' ' . $this->paiement->devise . 
                ' pour « ' . $motifIntention . ' » a été effectué avec succès.';

        // Structure FCM corrigée
        $payload = [
            'to' => $notifiable->fcm_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'icon' => 'ic_notification',
                'badge' => '1',
                'color' => '#4CAF50', // Vert pour indiquer le succès
            ],
            'data' => [
                'title' => $title,
                'body' => $body,
                'type' => 'paiement_reussi',
                'paiement_id' => (string) $this->paiement->id,
                'messe_id' => (string) $this->paiement->messe_id,
                'montant' => (string) $this->paiement->montant,
                'devise' => $this->paiement->devise,
                'motif_intention' => $motifIntention,
                'reference' => $this->paiement->reference ?? '',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'timestamp' => now()->toISOString(),
                'statut' => 'success',
            ],
            'priority' => 'high',
            'content_available' => true,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'payments_channel',
                    'icon' => 'ic_notification',
                    'color' => '#4CAF50', // Vert pour Android
                    'sound' => 'default',
                    'tag' => 'paiement_reussi_' . $this->paiement->id,
                    'vibrate' => [100, 100, 100], // Vibration de succès
                ],
            ],
            'apns' => [
                'payload' => [
                    'aps' => [
                        'content-available' => 1,
                        'sound' => 'default',
                        'badge' => 1,
                        'alert' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                    ],
                ],
                'headers' => [
                    'apns-priority' => '10',
                ],
            ],
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
                Log::info('✅ Notification FCM paiement réussi envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'messe_id' => $this->paiement->messe_id,
                    'montant' => $this->paiement->montant . ' ' . $this->paiement->devise,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                    'message_id' => $responseData['message_id'] ?? null,
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM paiement réussi', [
                    'user_id' => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'response' => $responseData,
                    'error' => $responseData['results'][0]['error'] ?? 'Unknown error',
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM paiement réussi', [
                'user_id' => $notifiable->id,
                'paiement_id' => $this->paiement->id,
                'messe_id' => $this->paiement->messe_id,
                'error' => $e->getMessage(),
                'token' => substr($notifiable->fcm_token, 0, 10) . '...',
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