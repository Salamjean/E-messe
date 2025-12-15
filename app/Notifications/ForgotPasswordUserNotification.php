<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotPasswordUserNotification extends Notification
{
    use Queueable;

    protected $code;

    /**
     * Crée une nouvelle notification avec le code OTP
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Définir les canaux de notification
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construction du mail
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Réinitialisation de mot de passe - E-Messe')
            ->view('emails.user_password_forgot', [
                'user' => $notifiable,
                'code' => $this->code,   // ⚡ clé "code" pour Blade
            ]);
    }
}
