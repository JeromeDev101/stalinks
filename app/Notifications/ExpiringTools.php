<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExpiringTools extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tools, $day;

    /**
     * Create a new notification instance.
     *
     * @param $tools
     * @param $day
     */
    public function __construct($tools, $day)
    {
        $this->tools = $tools;
        $this->day = $day;
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
        $subject = $this->day === 0 ? 'Tools Expired' : 'Tools Expiring';

        return (new MailMessage)
            ->subject($subject)
            ->markdown('tools.expiring_tools', [
                'day' => $this->day,
                'user' => $notifiable,
                'subject' => $subject,
                'tools' => $this->tools,
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
        $message = $this->day !== 0
            ? 'There are ' . count($this->tools) . ' tool(s) that will expire in ' . $this->day . ' days. Please renew them now!'
            : 'There are ' . count($this->tools) . ' tool(s) that are now expired. Please renew them now!';

        return [
            'message' => $message
        ];
    }
}
