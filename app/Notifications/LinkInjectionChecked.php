<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LinkInjectionChecked extends Notification
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
        if ($this->mode == 'buyer') {
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
            ->subject('Link Injection Checked')
            ->markdown('buyer.link_injection_checked', ['user' => $this->user, 'injection' => $this->injection]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->mode == 'buyer') {
            return [
                'message' => 'Your link injection request has been checked by the seller. Please check the request in the Follow Up Injection page.'
            ];
        } else {
            return [
                'message' => 'Link injection ID# ' . $this->injection->id . ' has been approved by the seller'
            ];
        }
    }
}
