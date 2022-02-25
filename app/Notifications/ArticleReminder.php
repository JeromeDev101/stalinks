<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ArticleReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $articles, $hours;

    /**
     * Create a new notification instance.
     *
     * @param $articles
     * @param $hours
     */
    public function __construct($articles, $hours)
    {
        $this->hours = $hours;
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
            ->subject('Articles In Writing Reminder')
            ->markdown('writer.articles_reminder_' . $this->hours, ['articles'=>$this->articles[$notifiable->id], 'user'=> $notifiable]);
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

        if ($this->hours !== 24) {
            $message = "You've accepted " . count($this->articles[$notifiable->id]) . " article(s)
            without content entry for the last " . $this->hours . " hours. Please note that all accepted articles
            without content will be automatically cancelled after 24 hours.";
        } else {
            $message = "There are " . count($this->articles[$notifiable->id]) . " accepted article(s) that were automatically
            cancelled and dequeue due to lack of content for the last 24 hours.";
        }

        return [
            'message' => $message
        ];
    }
}
