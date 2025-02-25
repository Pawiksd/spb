<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReleaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $release;

    public function __construct($release)
    {
        $this->release = $release;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Release: ' . $this->release->title)
            ->line('A new release is available: ' . $this->release->title)
            ->action('View Release', url('/releases/' . $this->release->id))
            ->line('Thank you for using our application!');
    }
}
