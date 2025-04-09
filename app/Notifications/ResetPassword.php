<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    protected $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, string $name)
    {
        $this->token = $token;
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
                    ->subject('Скидання паролю')
                    ->greeting('Вітаємо, ' . $this->name . '!')
                    ->line('Ви отримали цей лист, тому що ми отримали запит на скидання паролю для вашого облікового запису.')
                    ->action('Скинути пароль', $resetUrl)
                    ->line('Термін дії посилання для скидання паролю закінчується через 60 хвилин.')
                    ->line('Якщо ви не запитували скидання паролю, ніяких дій не потрібно.')
                    ->salutation('З повагою, команда School 61');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token
        ];
    }
}
