<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $verificationCode;
    protected $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $verificationCode, string $name)
    {
        $this->verificationCode = $verificationCode;
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
        return (new MailMessage)
                    ->subject('Підтвердіть вашу електронну пошту')
                    ->greeting('Вітаємо, ' . $this->name . '!')
                    ->line('Дякуємо вам за реєстрацію. Будь ласка, використовуйте наступний код для підтвердження вашої електронної пошти.')
                    ->line('Код підтвердження: **' . $this->verificationCode . '**')
                    ->line('Цей код буде дійсним протягом 60 хвилин.')
                    ->line('Якщо ви не створювали обліковий запис, ніяких дій не потрібно.')
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
            'code' => $this->verificationCode
        ];
    }
}
