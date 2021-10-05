<?php

namespace App\Console\Commands;

use App\Jobs\DeleteEmailAttachments;
use Illuminate\Console\Command;

class DeleteOldEmailAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_attachments:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete email attachments older than 30 days or 1 month';

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
        $this->info('Email attachments deletion command run');

        // call job to delete email attachments older than 30 days
        DeleteEmailAttachments::dispatch();

        $this->info('Email attachments deletion command finished');
    }
}
