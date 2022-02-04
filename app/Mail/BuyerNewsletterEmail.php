<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuyerNewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $urls, $buyer;

    /**
     * Create a new message instance.
     *
     * @param $buyer
     * @param $urls
     */
    public function __construct($buyer, $urls)
    {
        $this->urls = $urls;
        $this->buyer = $buyer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('buyer.newsletter')->subject('New URLs Uploaded');
    }
}
