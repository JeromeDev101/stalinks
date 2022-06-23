<?php

namespace App\Listeners;

use App\Events\ArticleCreatedEvent;
use App\Models\User;
use App\Notifications\ArticleCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class ArticleCreatedListener
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
     * @param  ArticleCreatedEvent  $event
     * @return void
     */
    public function handle(ArticleCreatedEvent $event)
    {
        // notify valid inter writers
        $internal = User::where('role_id', 4)->where('isOurs', 0)->where('status', 'active')->get();

        Notification::send($internal, new ArticleCreated($event->article));

        // notify external writers based on language
        $columns = [
            'users.*',
            'registration.language_id',
            'registration.is_exam_passed'
        ];

        $external = User::select($columns)
            ->where('role_id', 4)
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->where('users.isOurs', 1)
            ->where('users.status', 'active')
            ->where('registration.account_validation', 'valid')
            ->where('registration.is_exam_passed', 1)
            ->whereRaw("FIND_IN_SET(". $event->article->id_language .", REPLACE(REPLACE(language_id,'[',''), ']',''))")
            ->get();

        Notification::send($external, new ArticleCreated($event->article));
    }
}
