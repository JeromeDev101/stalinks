<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterPaid extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     *
     * @param $event
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject('Article Paid')
            ->markdown('writer.writer_paid', [
                'name' => $this->event->user->name,
                'price' => $this->event->price,
                'date' => Carbon::now()->format('m-d-Y'),
                'survey_code' => $this->event->user->registration->survey_code,
                'articles' => implode(', ', $this->event->articleIds)
            ])
            ->attach(config('app.url') . $this->event->receipt);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Your account has been credited of $' . $this->event->price . ' for the different order with Article IDs ['. implode(', ', $this->event->articleIds) .']'
        ];
    }
}
