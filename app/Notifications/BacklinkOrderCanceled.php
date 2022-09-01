<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkOrderCanceled extends Notification implements ShouldQueue
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
            ->subject('Order Canceled')
            ->markdown('buyer.order_canceled', [
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
            'message' => 'We regret to inform you that your order (Backlink ID# ' . $this->backlink->id . ') has been canceled.' . ' No worries! a total of $' . $this->backlink->prices . ' were automatically credited back to your account.'
        ];
    }
}
