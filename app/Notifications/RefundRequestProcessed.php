<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RefundRequestProcessed extends Notification
{
    use Queueable;

    protected $walletTransaction, $mode, $type;

    /**
     * Create a new notification instance.
     *
     * @param $walletTransaction
     * @param $mode
     * @param $type
     */
    public function __construct($walletTransaction, $mode, $type)
    {
        $this->walletTransaction = $walletTransaction;
        $this->mode = $mode;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->type === 'user') {
            return ['mail', 'database'];
        } else if ($this->type === 'team') {
            return ['database'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->mode === 'request' && $this->type === 'user') {
            return (new MailMessage)
                ->subject('Refund Request')
                ->markdown('buyer.refund_request', ['wallet' => $this->walletTransaction, 'mode' => $this->mode]);
        } else if ($this->mode === 'refund' && $this->type === 'user') {
            return (new MailMessage)
                ->subject('Refund Request Successful')
                ->markdown('buyer.refund_request', ['wallet' => $this->walletTransaction, 'mode' => $this->mode]);
        }
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
        $username = $this->walletTransaction->user->username
            ? $this->walletTransaction->user->username
            : $this->walletTransaction->user->name;

        $id = "(ID#" . $this->walletTransaction->user->id . ")";

        $username_id = $username . " " . $id;

        $amount = "$" . $this->walletTransaction->amount_usd;

        if ($this->mode === 'request' && $this->type === 'team') {
            $message = $username_id . " has requested to refund " . $amount;
        } else if ($this->mode === 'request' && $this->type === 'user') {
            $message = "You have successfully requested a refund of " . $amount;
        } else if ($this->mode === 'refund' && $this->type === 'user') {
            $message = "Your refund request of " . $amount . " has been successfully processed.";
        }

        return [
            'message' => $message
        ];
    }
}
