<?php

namespace App\Console\Commands;

use App\Jobs\UpdateStatusMailLogs;
use Illuminate\Console\Command;

class UpdateMailLogsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_logs_status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update mail logs status';

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
        $this->info('Mail log status update command run');

        // call job to update mail log status
        UpdateStatusMailLogs::dispatch();
    }
}
