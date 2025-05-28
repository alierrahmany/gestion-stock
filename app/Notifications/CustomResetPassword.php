<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $baseUrl = 'http://127.0.0.1:8000'; // hardcoded to avoid localhost issues

        $url = $baseUrl . route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false); // false = generate relative path only

        return (new MailMessage)
            ->subject('Réinitialisation de mot de passe Stockino')
            ->greeting('Bonjour!')
            ->line('Vous recevez cet email parce que nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
            ->action('Réinitialiser le mot de passe', $url)
            ->line('Ce lien expirera dans ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line("Si vous n'avez pas demandé de réinitialisation, ignorez simplement cet email.")
            ->salutation('Cordialement, L\'équipe Stockino');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
