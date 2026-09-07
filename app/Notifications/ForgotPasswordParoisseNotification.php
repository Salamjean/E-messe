<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordParoisseNotification extends Notification
{
    use Queueable;

    protected $code;
    protected $paroisse;

    /**
     * Crée une nouvelle notification avec le code OTP pour la paroisse
     */
    public function __construct($code, $paroisse = null)
    {
        $this->code = $code;
        $this->paroisse = $paroisse;
    }

    /**
     * Définir les canaux de notification
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construction du mail
     */
    public function toMail($notifiable): MailMessage
    {
        $paroisse = $this->paroisse ?? $notifiable;

        return (new MailMessage)
            ->subject('Réinitialisation de mot de passe - Paroisse E-Messe')
            ->view('emails.paroisse_password_forgot', [
                'paroisse' => $paroisse,
                'code' => $this->code,
            ]);
    }
}
