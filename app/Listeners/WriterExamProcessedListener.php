<?php

namespace App\Listeners;

use App\Events\WriterExamProcessedEvent;
use App\Models\User;
use App\Notifications\WriterExamProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class WriterExamProcessedListener
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
     * @param  WriterExamProcessedEvent  $event
     * @return void
     */
    public function handle(WriterExamProcessedEvent $event)
    {
        if ($event->mode === 'setup' || $event->mode === 'approved' || $event->mode === 'disapproved') {
            // notify writer that if exam is created/passed/failed
            $event->writerExam->writer->notify(new WriterExamProcessed($event->writerExam, $event->mode, $event->reason));
        } else if ($event->mode === 'checking') {
            //  send notification to team that exam is submitted
            $team = User::whereIn('role_id', [8,1,6,4])->where('isOurs', 0)->where('status', 'active')->get();

            Notification::send($team, new WriterExamProcessed($event->writerExam, $event->mode));
        }
    }
}
