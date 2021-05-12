<?php

namespace App\Jobs;

use App\Models\Publisher;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckIfUrlHasHttps implements ShouldQueue
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
        $guzzle = new Client(['defaults' => [ 'exceptions' => false ]]);

        $publishers = Publisher::whereNull('is_https')->get();

        foreach ($publishers as $publisher) {
            $url = '';

            try {
                $guzzle->request('GET', trim_special_characters($publisher->url), [
                    'on_stats' => function (TransferStats $stats) use (&$url) {
                        $url = $stats->getHandlerStats()['url'];
                    }
                ]);

                $publisher->update([
                    'is_https' => explode(':', $url)[0] == 'http' ? 'no' : 'yes',
                ]);
            } catch (\Exception $exception) {
                \Log::error($exception);
            }
        }
    }
}
