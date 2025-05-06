<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TokenRenewed extends Notification
{
    use Queueable;
    public $tokens, $credits;

    /**
     * Create a new notification instance.
     */
    public function __construct($tokens, $credits)
    {
        $this->tokens = $tokens;
        $this->credits = $credits;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // This means: store it in the database
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your subscription have been renewed! You received {$this->tokens} tokens and {$this->credits} image credits.",
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
