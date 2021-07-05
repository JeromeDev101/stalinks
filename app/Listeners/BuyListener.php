<?php

namespace App\Listeners;

use App\Events\BuyEvent;
use App\Notifications\Buy;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BuyListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BuyEvent $event)
    {
        $event->user->notify(new Buy($event->backlink));
    }
}
