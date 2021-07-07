<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BuyerDebitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $totalAmount, $backlinkIds;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($totalAmount, $backlinkIds)
    {
        $this->totalAmount = $totalAmount;
        $this->backlinkIds = $backlinkIds;
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
            'message' => 'Your account has been debited of $' . $this->totalAmount . ' for the different orders ['. implode(', ', $this->backlinkIds) .'].'
        ];
    }
}
