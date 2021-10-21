<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkLiveSeller extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink;
    protected $seller;

    /**
     * Create a new notification instance.
     *
     * @param $backlink
     * @param $seller
     */
    public function __construct($backlink, $seller)
    {
        $this->backlink = $backlink;
        $this->seller = $seller;
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
            ->subject('Order Live')
            ->markdown('seller.backlink_live', ['backlink'=>$this->backlink, 'user'=>$this->seller]);
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
            'message' => 'Your backlink order ID ' . $this->backlink->id . ' is completed.'
        ];
    }
}
