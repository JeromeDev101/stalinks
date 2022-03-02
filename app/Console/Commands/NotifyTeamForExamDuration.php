<?php

namespace App\Console\Commands;

use App\Jobs\NotifyTeamWriterExamDurationOver;
use Illuminate\Console\Command;

class NotifyTeamForExamDuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam-duration:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to team when writer exam duration for 2nd attempt is done';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start sending articles on queue to writers');

        // dispatch job to send notification to team when 2nd attempt exam duration for writer is over
        NotifyTeamWriterExamDurationOver::dispatch()->onQueue('emails');

        $this->info('Sending articles on queue emails end');
    }
}
