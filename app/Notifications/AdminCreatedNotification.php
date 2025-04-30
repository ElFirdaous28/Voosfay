<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminCreatedNotification extends Notification
{
    use Queueable;
    public $admin;
    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($admin, $password)
    {
        $this->admin = $admin;
        $this->password = $password;
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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->admin->name)
            ->line('You have been assigned as an admin on the platform.')
            ->line('Your login credentials are as follows:')
            ->line('Email: ' . $this->admin->email)
            ->line('Password: ' . $this->password)
            ->action('Login', 'http://localhost:5173/login')
            ->line('Please change your password after logging in for the first time.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
