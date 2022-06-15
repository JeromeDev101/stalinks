<?php

namespace App\Console\Commands;

use App\Jobs\SendNewsletterToBuyers;
use Illuminate\Console\Command;

class SendNewsletterToSubscribedBuyers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buyer-newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newsletter updates for subscribed buyers';

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
     * @return mixed|void
     */
    public function handle()
    {

        $this->info('Start sending newsletter to subscribed buyers');

        // dispatch job to send newsletters to subscribed buyers
        SendNewsletterToBuyers::dispatch()->onQueue('high');

        $this->info('Sending newsletter end');
    }
}
