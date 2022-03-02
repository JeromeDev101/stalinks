<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterSecondExamDurationOver extends Notification implements ShouldQueue
{
    use Queueable;

    protected $writers;

    /**
     * Create a new notification instance.
     *
     * @param $writers
     */
    public function __construct($writers)
    {
        $this->writers = $writers;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = '';

        if (count($this->writers) <= 5) {
            $writer_names = implode(', ', $this->writers);

            $message = "You can now create a second attempt writer examination for writers (" . $writer_names . ").";
        } else {
            $message = "You can now create a second attempt writer examination for " . count($this->writers) . " writer(s)";
        }

        return [
            'message' => $message
        ];
    }
}
