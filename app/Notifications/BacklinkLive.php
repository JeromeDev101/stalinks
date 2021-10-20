<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkLive extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param $backlink
     * @param $user
     */
    public function __construct($backlink, $user)
    {
        $this->backlink = $backlink;
        $this->user = $user;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Order Live')
            ->markdown('buyer.backlink_live', ['backlink'=>$this->backlink, 'user'=>$this->user]);
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
            'message' => 'Your purchase of Backlink ID ' . $this->backlink->id . ' from ' . $this->backlink->url_advertiser . ' is Live.'
        ];
    }
}
