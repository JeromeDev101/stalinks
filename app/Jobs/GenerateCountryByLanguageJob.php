<?php

namespace App\Jobs;

use App\Models\Publisher;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;

class GenerateCountryByLanguageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $ids;

    private $countries;

    /**
     * Create a new job instance.
     *
     * @param array $ids
     */
    public function __construct($ids = [])
    {
        $this->ids = $ids;
        $this->countries = Config::get('constant.language_country');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $publishers = Publisher::whereIn('id', $this->ids)->get();

        foreach ($publishers as $publisher) {
            if (isset($this->countries[$publisher->language_id])) {
                $publisher->update([
                    'country_id' => $this->countries[$publisher->language_id]
                ]);
            }
        }
    }
}
