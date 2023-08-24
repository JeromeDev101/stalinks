<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LinkInjectionSellerUpdated extends Notification
{
    use Queueable;
    protected $user, $injection, $new_injection, $mode;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $injection, $new_injection, $mode)
    {
        $this->user = $user;
        $this->mode = $mode;
        $this->injection = $injection;
        $this->new_injection = $new_injection;
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
        if ($this->mode == 'seller') {
            return (new MailMessage)
                ->subject('Link Injection Request Canceled')
                ->markdown('seller.link_injection_seller_updated', [
                    'user' => $this->user,
                    'injection' => $this->injection,
                ]);
        } else {
            return (new MailMessage)
                ->subject('Link Injection Request Seller Updated')
                ->markdown('buyer.link_injection_seller_updated', [
                    'user' => $this->user,
                    'injection' => $this->injection,
                    'new_injection' => $this->new_injection,
                ]);
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
        if ($this->mode == 'seller') {
            return [
                'message' => 'We are sorry to inform you that link injection request ID# ' . $this->injection->id . ' associated with your account has been automatically canceled because we were unable to reach you. Please check the details in the "Injection Requests" page.'
            ];
        } else {
            return [
                'message' => 'Your link injection request ID# ' . $this->injection->id . ' has been automatically canceled because we cannot reach the seller. A new link injection request (ID# ' . $this->new_injection->id . ') has been generated automatically to a new seller. Please check the details in the "Follow up Injection" page. We are sorry for the inconvenience.'
            ];
        }
    }
}
