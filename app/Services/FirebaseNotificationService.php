<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = app('firebase.messaging');
    }

    /**
     * Envoie une notification à un appareil spécifique.
     *
     * @param string $fcmToken Le token FCM de l'appareil.
     * @param string $title    Le titre de la notification.
     * @param string $body     Le corps de la notification.
     * @param array|null $data Les données supplémentaires à envoyer.
     */
    public function sendNotification(string $fcmToken, string $title, string $body, array $data = null)
    {
        if (empty($fcmToken)) {
            return; // Ne rien faire si le token est vide
        }

        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification($notification);

        if ($data) {
            $message = $message->withData($data);
        }

        try {
            $this->messaging->send($message);
        } catch (\Exception $e) {
            // Gérer les erreurs, par exemple, si le token est invalide
            \Log::error('Erreur envoi notification FCM: ' . $e->getMessage());
        }
    }
}