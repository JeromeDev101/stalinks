<?php

namespace App\Listeners;

use App\Events\ArticleDoneEvent;
use App\Notifications\ArticleDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleDoneListener
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
    public function handle(ArticleDoneEvent $event)
    {
        $event->user->notify(new ArticleDone($event->article));
    }
}
