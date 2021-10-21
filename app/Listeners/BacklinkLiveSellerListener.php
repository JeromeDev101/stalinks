<?php

namespace App\Listeners;

use App\Events\BacklinkLiveSellerEvent;
use App\Notifications\BacklinkLiveSeller;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BacklinkLiveSellerListener
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
     * @param  BacklinkLiveSellerEvent  $event
     * @return void
     */
    public function handle(BacklinkLiveSellerEvent $event)
    {
        $event->seller->notify(new BacklinkLiveSeller($event->backlink, $event->seller));
    }
}
