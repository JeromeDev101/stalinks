<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LinkInjectionStatusChanged extends Notification
{
    use Queueable;
    protected $user, $injection, $mode, $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $injection, $mode, $status)
    {
        $this->user = $user;
        $this->mode = $mode;
        $this->status = $status;
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
        if ($this->mode == 'buyer') {
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
        $subject = '';

        if ($this->status == 'issue') {
            $subject = 'Link Injection Status Updated';
        } else if ($this->status == 'canceled') {
            $subject = 'Link Injection Request Canceled';
        } else if ($this->status == 'live') {
            $subject = 'Link Injection Request is Live';
        }

        return (new MailMessage)
            ->subject($subject)
            ->markdown('buyer.link_injection_status_updated', [
                'user' => $this->user,
                'injection' => $this->injection,
                'status' => $this->status
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
        if ($this->mode == 'buyer') {
            $message = '';

            if ($this->status == 'issue') {
                $message = "Link injection request ID# " . $this->injection->id . "'s status has been updated to 'Issue'. Please check the details in the 'Follow up Injection' page.";
            } else if ($this->status == 'canceled') {
                $message = 'We regret to inform you that your link injection request (ID# ' . $this->injection->id . ') has been canceled.' . ' No worries! a total of $' . $this->injection->buyer_injection_price . ' were automatically credited back to your account.';
            } else if ($this->status == 'live') {
                $message = "Your link injection request (ID# " . $this->injection->id . ") is now live!. Please check the details in the 'Follow up Injection' page. ";
            }

            return [
                'message' => $message
            ];
        } else {
            $message = '';

            if ($this->status == 'issue') {
                $message = "The seller for link injection request ID# " . $this->injection->id . "' updated the status to 'Issue'. Please check the details in the seller 'Injection Requests' page.";
            } else if ($this->status == 'canceled') {
                $message = "The seller for link injection request (ID# " . $this->injection->id . ") has canceled the order. Please check the details in the seller 'Injection Requests' page.";
            } else if ($this->status == 'live') {
                $message = "Link injection request (ID# " . $this->injection->id . ") is now live!. Please check the details in the seller 'Injection Requests' page. ";
            }

            return [
                'message' => $message
            ];
        }
    }
}
