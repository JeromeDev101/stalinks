<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SellerConfirmedPendingOrderBuyer extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink, $confirmation;

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
        $seller = $this->backlink->publisher->user ? 'Seller ' . $this->backlink->publisher->user->username : 'The seller';

        return (new MailMessage)
            ->subject('Pending Order Confirmation')
            ->markdown('buyer.pending_order_confirmed', [
                'seller' => $seller,
                'name' => $notifiable->name,
                'backlink' => $this->backlink,
                'confirmation' => $this->confirmation
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
        $message = '';
        $seller = $this->backlink->publisher->user ? 'Seller ' . $this->backlink->publisher->user->username : 'The seller';
        $confirmation_text = $this->confirmation === 'approve' ? 'confirmed' : 'cancelled';

        if ($this->confirmation === 'approve') {
            $message = ". The order is now on 'Processing' status.";
        } else {
            $message = ". No worries! a total of $" . $this->backlink->prices . " were automatically credited back to your account.";
        }

        return [
            'message' => $seller . " has " . $confirmation_text . " your pending order: backlink ID# " . $this->backlink->id . $message
        ];
    }
}
