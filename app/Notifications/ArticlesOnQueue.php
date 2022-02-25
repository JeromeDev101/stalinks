<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArticlesOnQueue extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user, $articles;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $articles
     */
    public function __construct($user, $articles)
    {
        $this->user = $user;
        $this->articles = $articles;
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
            ->subject('Articles on Queue')
            ->markdown('writer.queue_articles', ['articles'=>$this->articles, 'user'=>$this->user]);
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
            'message' => 'There are ' . count($this->articles) . ' articles on queue that you can accept and write.'
        ];
    }
}
