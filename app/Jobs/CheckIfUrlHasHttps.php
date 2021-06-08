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
        \Log::channel('slack')->info('----------START-----------');
        $guzzle = new Client(['defaults' => [ 'exceptions' => false]]);
        Publisher::whereNull('is_https')->chunk(500, function ($publishers) use ($guzzle) {
            \Log::channel('slack')->info('LOADED 500 DATA');
            foreach ($publishers as $publisher) {
                $url = '';

                try {
                    \Log::channel('slack')->info('START URL: ' . $publisher->url);
                    $guzzle->request('GET', trim_special_characters($publisher->url), [
                        'on_stats' => function (TransferStats $stats) use (&$url) {
                            $url = $stats->getHandlerStats()['url'];
                        },
                        'timeout' => 10
                    ]);

                    \Log::channel('slack')->info('END URL: ' . $url);

                    if ($url != '') {
                        $publisher->update([
                            'is_https' => explode(':', $url)[0] == 'http' ? 'no' : 'yes',
                        ]);
                    }
                } catch (\Exception $exception) {
                    $publisher->update([
                        'is_https' => 'N/A'
                    ]);
                    \Log::channel('slack')->info($exception->getMessage());
                    \Log::error($exception);
                }
            }
        });

        \Log::channel('slack')->info('----------END-----------');
    }
}
