<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $title;
    private $content;
    private $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $email, $title, $content)
    {
        $this->user = $user;
        $this->title = $title;
        $this->content = $content;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from($this->user->work_mail)
            ->subject($this->title)
            ->to($this->email)
            ->view('mailext', ['content' => $this->content]);

        Log::info($this->user->work_mail. 'has sent to '. $this->email);
        return $this;
    }
}
