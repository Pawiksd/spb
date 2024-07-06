<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSongRelease extends Notification
{
    use Queueable;

    protected $song;

    public function __construct($song)
    {
        $this->song = $song;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('New song released: ' . $this->song->title)
                    ->action('Listen Now', url('/songs/' . $this->song->id))
                    ->line('Thank you for using our application!');
    }
}
