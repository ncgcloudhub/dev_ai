<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelled extends Notification
{
    use Queueable;

    public $subscriptionName;

    /**
     * Create a new notification instance.
     */
    public function __construct($subscriptionName)
    {
        $this->subscriptionName = $subscriptionName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // Store the notification in the database
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your subscription ({$this->subscriptionName}) has been successfully cancelled.",
        ];
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
