<?php

namespace App\Console\Commands;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FlushSystemLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system-logs:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete 3 months+ old data';

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
        $logs = Log::where('created_at', '<', Carbon::now()->subMonth(3))->get();

        if ($logs) {
            Log::where('created_at', '<', Carbon::now()->subMonth(3))->delete();
        }
    }
}
