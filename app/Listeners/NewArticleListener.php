<?php

namespace App\Listeners;

use App\Events\NewArticleEvent;
use App\Notifications\NewArticle;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewArticleListener
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
    public function handle(NewArticleEvent $event)
    {
        $event->user->notify(new NewArticle($event->article));
    }
}
