<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentCredentials extends Notification implements ShouldQueue
{
    use Queueable;

    protected $password;
    protected $name;

    /**
     * Create a new notification instance.
     * 
     * @param string $password
     * @param string $name
     */
    public function __construct(string $password, string $name)
    {
        $this->password = $password;
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
                    ->from('school@example.com', 'School 61')
                    ->subject('Доступ до шкільного порталу')
                    ->greeting('Вітаємо, ' . $this->name . '!')
                    ->line('Для Вас було створено обліковий запис у шкільному порталі.')
                    ->line('Використовуйте наступні дані для входу:')
                    ->line('Email: **' . $notifiable->email . '**')
                    ->line('Пароль: **' . $this->password . '**')
                    ->line('Рекомендуємо змінити пароль після першого входу в систему.')
                    ->action('Увійти в систему', url('/login'))
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
            'password' => $this->password
        ];
    }
} 