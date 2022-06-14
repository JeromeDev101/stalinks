<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Config;

class ResetAlexaCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alexa:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetting Alexa limit every month';

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
        $config = Config::where('code', 'alexa_consume')->first();
        $config->update(['value'=> '0']);
    }
}
