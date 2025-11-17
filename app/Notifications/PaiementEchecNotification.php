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
            'type' => 'paiement_echec',
            'title' => 'Échec du paiement ❌',
            'body' => 'Votre paiement de ' . $montantFormatted . ' ' . $this->paiement->devise . ' a échoué. Veuillez réessayer.',
            'paiement_id' => $this->paiement->id,
            'messe_id' => $this->paiement->messe_id,
            'montant' => $this->paiement->montant,
            'devise' => $this->paiement->devise,
            'motif_intention' => $this->paiement->messe->motif_intention ?? 'Intention inconnue',
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

        $title = 'Échec du paiement ❌';
        $body = 'Votre paiement de ' . $montantFormatted . ' ' . $this->paiement->devise . 
                ' pour « ' . $motifIntention . ' » a échoué. Veuillez réessayer.';

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
                'color' => '#FF3B30', // Rouge pour indiquer un échec
            ],
            'data' => [
                'title' => $title,
                'body' => $body,
                'type' => 'paiement_echec',
                'paiement_id' => (string) $this->paiement->id,
                'messe_id' => (string) $this->paiement->messe_id,
                'montant' => (string) $this->paiement->montant,
                'devise' => $this->paiement->devise,
                'motif_intention' => $motifIntention,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'timestamp' => now()->toISOString(),
                'urgence' => 'high', // Indique que c'est important
            ],
            'priority' => 'high',
            'content_available' => true,
            'android' => [
                'priority' => 'high',
                'notification' => [
                    'channel_id' => 'payments_channel', // Canal dédié aux paiements
                    'icon' => 'ic_notification',
                    'color' => '#FF3B30', // Rouge pour Android
                    'sound' => 'default',
                    'tag' => 'paiement_echec_' . $this->paiement->id, // Évite les doublons
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
                    'apns-priority' => '10', // Haute priorité pour iOS
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
                Log::info('✅ Notification FCM échec paiement envoyée avec succès', [
                    'user_id' => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'messe_id' => $this->paiement->messe_id,
                    'montant' => $this->paiement->montant . ' ' . $this->paiement->devise,
                    'token' => substr($notifiable->fcm_token, 0, 10) . '...',
                    'message_id' => $responseData['message_id'] ?? null,
                ]);
            } else {
                Log::warning('❌ Échec envoi notification FCM échec paiement', [
                    'user_id' => $notifiable->id,
                    'paiement_id' => $this->paiement->id,
                    'response' => $responseData,
                    'error' => $responseData['results'][0]['error'] ?? 'Unknown error',
                ]);
            }

            return $responseData;

        } catch (\Exception $e) {
            Log::error('💥 Erreur envoi FCM échec paiement', [
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