<?php

namespace App\Console\Commands;

use App\Jobs\SendArticlesQueueEmailToWriters;
use Illuminate\Console\Command;

class SendArticleReminderForWriters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article-queue:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification for writers for articles that are on queue';

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

        // dispatch job to send newsletters to subscribed buyers
        SendArticlesQueueEmailToWriters::dispatch()->onQueue('emails');

        $this->info('Sending articles on queue emails end');
    }
}
