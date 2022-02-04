<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddWalletCredit extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $amount;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $amount
     */
    public function __construct($user, $amount)
    {
        $this->user = $user;
        $this->amount = $amount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            ->subject('Wallet Credited')
            ->markdown('buyer.add_wallet', ['amount' => $this->amount, 'user' => $this->user]);
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
            'message' => 'Your wallet has been credited with the amount of $' . $this->amount . '.'
        ];
    }
}
