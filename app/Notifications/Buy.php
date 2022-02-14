<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Buy extends Notification implements ShouldQueue
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
    public function __construct($backlink, User $user = null)
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
        $registration = $this->user->registration;

        $user = $this->user ? $this->user->name : 'User';

        return (new MailMessage)
            ->subject('Purchase Successful')
            ->markdown('buyer.purchase_success', ['backlink'=>$this->backlink, 'user'=>$user, 'registration' => $registration]);
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
            'message' => 'You have purchased from ' . $this->backlink->url_advertiser . ' on ' . date('Y-m-d') . ' at $' . $this->backlink->prices . '. Follow up with Backlink ID ' . $this->backlink->id
        ];
    }
}
