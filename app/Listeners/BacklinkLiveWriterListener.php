<?php

namespace App\Listeners;

use App\Events\BacklinkLiveWriterEvent;
use App\Notifications\BacklinkLiveWriter;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BacklinkLiveWriterListener
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
     * @param  BacklinkLiveWriterEvent  $event
     * @return void
     */
    public function handle(BacklinkLiveWriterEvent $event)
    {
        $event->writer->notify(new BacklinkLiveWriter($event->backlink, $event->writer));
    }
}
