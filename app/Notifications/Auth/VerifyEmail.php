<?php

namespace App\Notifications\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verificar dirección de correo electrónico')
            ->greeting('¡Hola!')
            ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
            ->action('Verificar correo electrónico', $verificationUrl)
            ->line('Si no creaste una cuenta, no es necesario realizar ninguna acción.')
            ->salutation('¡Saludos!');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
