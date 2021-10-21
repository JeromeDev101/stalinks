<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BacklinkLiveWriter extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backlink;
    protected $writer;

    /**
     * Create a new notification instance.
     *
     * @param $backlink
     * @param $writer
     */
    public function __construct($backlink, $writer)
    {
        $this->backlink = $backlink;
        $this->writer = $writer;
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
            ->subject('Article Live')
            ->markdown('writer.backlink_live', ['backlink'=>$this->backlink, 'user'=>$this->writer]);
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
            'message' => 'Your article for backlink order ID ' . $this->backlink->id . ' is now live.'
        ];
    }
}
