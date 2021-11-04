<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
//    use Queueable;

    // change as you want
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }
        return (new MailMessage)
            ->greeting('Olá!')
            ->subject('Verifique seu endereço de e-mail')
            ->line('Por favor, clique no botão para verificar seu endereço de e-mail.')
            ->action(
                'Verifique seu endereço de e-mail',
                $this->verificationUrl($notifiable)
            )
            ->line('Se você não criou nenhuma conta, desconsidere este e-mail.');
    }
}
