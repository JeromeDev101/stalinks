<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendExpiringToolsNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiring-tools-notification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to team for expiring tools';

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
        $this->info('Start sending expiring tools notification to team');

        // dispatch job to send newsletters to subscribed buyers
       \App\Jobs\SendExpiringToolsNotification::dispatch()->onQueue('high');

        $this->info('Sending expiring tools notification end');
    }
}
