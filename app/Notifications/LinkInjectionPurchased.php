<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LinkInjectionPurchased extends Notification
{
    use Queueable;
    protected $user, $injection, $mode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $injection, $mode)
    {
        $this->user = $user;
        $this->mode = $mode;
        $this->injection = $injection;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->mode == 'seller') {
            return ['mail', 'database'];
        } else {
            return ['database'];
        }
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
            ->subject('Link Injection Purchased')
            ->markdown('seller.link_injection_purchased', ['user' => $this->user, 'injection' => $this->injection]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->mode == 'seller') {
            return [
                'message' => 'The buyer has made a purchase for link injection request ID# ' . $this->injection->id . '. Please verify the request on the Injection Requests page.'
            ];
        } else {
            return [
                'message' => 'Link injection ID# ' . $this->injection->id . ' has been purchased by the buyer.'
            ];
        }
    }
}
