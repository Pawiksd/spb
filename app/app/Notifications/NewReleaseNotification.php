<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReleaseNotification extends Notification
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
            ->line('A new song has been released: ' . $this->release->title)
            ->line('Artist: ' . $this->release->artist->name)
            ->line('Release Date: ' . $this->release->release_date)
            ->action('Listen Now', url('/releases/' . $this->release->id))
            ->line('Thank you for using our application!');
    }
}
