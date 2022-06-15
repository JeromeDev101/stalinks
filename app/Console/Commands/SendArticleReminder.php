<?php

namespace App\Console\Commands;

use App\Jobs\SendArticleReminderToWriters;
use Illuminate\Console\Command;

class SendArticleReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send article reminder to writers with in writing article';

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
        $this->info('Start sending in writing article reminders to writers');

        // dispatch job to send in writing article reminders to writers
        SendArticleReminderToWriters::dispatch()->onQueue('high');

        $this->info('Sending in writing articles emails end');
    }
}
