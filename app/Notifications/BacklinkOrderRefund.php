<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkOrderRefund extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink;

    /**
     * Create a new notification instance.
     *
     * @param $backlink
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
            ->subject('Refund Order')
            ->markdown('buyer.order_refund', [
                'name' => $notifiable->name ?: $notifiable->username,
                'backlink' => $this->backlink,
            ]);
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
            'message' => 'Your order (Backlink ID# ' . $this->backlink->id . ') has been refunded.' . ' Your currently total of $' . $this->backlink->prices . ' were automatically credited back to your account.'
        ];
    }
}
