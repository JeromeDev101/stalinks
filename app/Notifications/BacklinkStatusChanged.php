<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($backlink)
    {
        $this->backlink = $backlink;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
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
        return [
            'message' => 'Your order number with Backlink ID ' . $this->backlink->id . ' from ' . $this->backlink->url_advertiser . ' has now the status of ' . $this->backlink->status . '.'
        ];
    }
}
