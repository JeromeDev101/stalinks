<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\WriterSecondExamDurationOver;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class NotifyTeamWriterExamDurationOver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $writers = $this->getWritersWithExamDurationOver();

        // send notification to team
        if ($writers) {
            $team = User::whereIn('role_id', [8,1,6,4])->where('isOurs', 0)->where('status', 'active')->get();

            Notification::send($team, new WriterSecondExamDurationOver($writers));
        }
    }

    /**
     * get writers with 2nd attempt exam duration = today
     *
     * @return array
     */
    protected function getWritersWithExamDurationOver () {
        return User::where('role_id', 4)
            ->where('isOurs', 1)
            ->where('status', 'active')
            ->whereNotNull('exam_second_attempt_at')
            ->whereDate('exam_second_attempt_at', Carbon::today())
            ->get()
            ->pluck('username')
            ->toArray();
    }


}
