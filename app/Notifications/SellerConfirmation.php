<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellerConfirmation extends Notification
{
    use Queueable;

    protected $backlink;
    protected $seller;

    /**
     * Create a new notification instance.
     *
     * @return void
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seller Confirmation')
            ->markdown('seller.confirmation', ['backlink'=>$this->backlink, 'user'=>$this->seller]);
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
            'message' => 'Your URL with order ID of ' . $this->backlink->id . ' is on Pending status. Please check your email to validate the order.'
        ];
    }
}
