<?php

namespace App\Listeners;

use App\Events\UserValidateEvent;
use App\Models\User;
use App\Notifications\UserValidated;
use App\Notifications\WriterValidated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class UserValidateListener
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
     * @param  UserValidateEvent  $event
     * @return void
     */
    public function handle(UserValidateEvent $event)
    {
        if ($event->user) {

            // if writer - send notification to team to create exam
            if ($event->user->role_id === 4 && $event->user->isOurs === 1) {
                $team = User::whereIn('role_id', [8,1,6,4])->where('isOurs', 0)->where('status', 'active')->get();

                Notification::send($team, new WriterValidated($event->input, $event->user));
            }

            $event->user->notify(new UserValidated($event->input, $event->user));
        }
    }
}
