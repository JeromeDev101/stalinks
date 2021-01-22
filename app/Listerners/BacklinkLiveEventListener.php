<?php

namespace App\Listerners;

use App\Events\BacklinkLiveEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BacklinkLiveEventListener
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
     * @param  BacklinkLiveEvent  $event
     * @return void
     */
    public function handle(BacklinkLiveEvent $event)
    {
        //
    }
}
