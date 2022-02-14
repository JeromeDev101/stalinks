<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellerConfirmedPendingOrder extends Notification
{
    use Queueable;

    protected $backlink;
    protected $confirmation;

    /**
     * Create a new notification instance.
     *
     * @param $backlink
     * @param $confirmation
     */
    public function __construct($backlink, $confirmation)
    {
        $this->backlink = $backlink;
        $this->confirmation = $confirmation;
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
        $seller = $this->backlink->publisher->user ? $this->backlink->publisher->user->username : 'A seller';
        $confirmation_text = $this->confirmation === 'approve' ? 'confirmed' : 'cancelled';

        return [
            'message' => $seller . ' has ' . $confirmation_text . ' a pending order: backlink ID# ' . $this->backlink->id
        ];
    }
}
