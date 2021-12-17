<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterPaid extends Notification
{
    use Queueable;

    protected $price, $articleIds;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($price, $articleIds)
    {
        $this->price = $price;
        $this->articleIds = $articleIds;
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
        return (new MailMessage)
            ->subject('Article Paid')
            ->markdown('writer.article_paid', [
                'price' => $this->price,
                'articles' => implode(', ', $this->articleIds)
            ]);
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
            'message' => 'Your account has been credited of $' . $this->price . ' for the different order with Article IDs ['. implode(', ', $this->articleIds) .']'
        ];
    }
}
