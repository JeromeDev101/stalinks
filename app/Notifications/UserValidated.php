<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserValidated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $input;

    /**
     * Create a new notification instance.
     *
     * @param $input
     * @param $user
     */
    public function __construct($input, $user)
    {
        $this->input = $input;
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
        if ($this->input['type'] === 'Buyer') {
            return (new MailMessage)
                ->subject('Account Validated')
                ->markdown('buyer.validate', ['name'=>$this->input['name']]);
        } else if ($this->input['type'] === 'Seller') {
            return (new MailMessage)
                ->subject('Account Validated')
                ->markdown('seller.validate', ['name'=>$this->input['name']]);
        } else {
            return (new MailMessage)
                ->subject('Account Validated')
                ->markdown('writer.validate', ['name'=>$this->input['name']]);
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
        return [
            'message' => 'Your account is now validated. Please re-login your account to activate.'
        ];
    }
}
