<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WriterBillingReuploadDoc extends Notification implements ShouldQueue
{
    use Queueable;

    protected $billing;
    protected $receipt;

    /**
     * Create a new notification instance.
     *
     * @param $billing
     * @param $receipt
     */
    public function __construct($billing, $receipt)
    {
        $this->receipt = $receipt;
        $this->billing = $billing;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject('Article Receipt Re-uploaded')
            ->markdown('writer.writer_billing_reuploaded', [
                'name' => $notifiable->name,
                'article' => $this->billing->article->toArray(),
                'date' => Carbon::now()->format('m-d-Y')
            ])
            ->attach(config('app.url') . '/images/billing/' . $this->receipt);
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
            //
        ];
    }
}
