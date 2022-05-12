<?php

namespace App\Listeners;

use App\Events\RefundRequestProcessedEvent;
use App\Models\User;
use App\Notifications\RefundRequestProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class RefundRequestProcessedListener
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
     * @param  RefundRequestProcessedEvent  $event
     * @return void
     */
    public function handle(RefundRequestProcessedEvent $event)
    {
        if ($event->mode === 'request') {
            // notify user
            $event->walletTransaction->user->notify(new RefundRequestProcessed($event->walletTransaction, $event->mode, 'user'));

            // notify admin and qc manager
            $team = User::whereIn('role_id', [8,1])->where('isOurs', 0)->where('status', 'active')->get();

            Notification::send($team, new RefundRequestProcessed($event->walletTransaction, $event->mode, 'team'));
        } else if ($event->mode === 'refund') {
            // notify user that refund request is successful
            $event->walletTransaction->user->notify(new RefundRequestProcessed($event->walletTransaction, $event->mode, 'user'));
        }
    }
}
