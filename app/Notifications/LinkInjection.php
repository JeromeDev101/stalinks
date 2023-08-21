<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LinkInjection extends Notification
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
                    ->subject('Link Injection Requested')
                    ->markdown('seller.link_injection', ['user' => $this->user, 'injection' => $this->injection]);
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
                'message' => 'A new link injection has been requested. Please check the request in the Injection Requests page.'
            ];
        } else {
            return [
                'message' => 'A new link injection has been requested to seller ID# ' . $this->user->id . ' ' . $this->user->username . '.'
            ];
        }
    }
}
