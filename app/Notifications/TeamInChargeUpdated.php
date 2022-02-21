<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TeamInChargeUpdated extends Notification
{
    use Queueable;

    protected $user;
    protected $multiple;
    protected $team_in_charge;

    /**
     * Create a new notification instance.
     *
     * @param $team_in_charge
     * @param $user
     * @param $multiple
     */
    public function __construct($team_in_charge, $user, $multiple = null)
    {
        $this->user = $user;
        $this->multiple = $multiple;
        $this->team_in_charge = $team_in_charge;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->multiple != null) {
            return [
                'message' => 'You are now assigned as the in charge for user(s) with User id(s): ' . implode(",", $this->multiple)
            ];
        } else {
            return [
                'message' => 'You are now assigned as the in charge for ' . $this->user->username . ' (User ID# ' . $this->user->id . ').'
            ];
        }
    }
}
