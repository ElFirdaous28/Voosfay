<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendWarning extends Notification
{
    use Queueable;
    

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
                    ->subject('Warning: A Report Has Been Filed Against You')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('We wanted to inform you that a report has been filed against you.')
                    ->line('Our team is currently reviewing the situation, and in case we receive more reports, we may take action such as suspension or ban.')
                    ->line('We advise you to stay aware of your actions and ensure you follow the platform\'s guidelines.')
                    ->action('View Your Profile', url('/profile/' . $notifiable->id))
                    ->line('Thank you for your attention.')
                    ->salutation('Regards, Platform Team');
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
