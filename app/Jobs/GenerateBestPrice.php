<?php

namespace App\Jobs;

use App\Models\Publisher;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateBestPrice implements ShouldQueue
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
        $duplicates = Publisher::select('url')->groupBy('url')->havingRaw('count(*) > 1')->get()->pluck('url');

        //Loop through each duplicate url
        foreach ($duplicates as $url) {
            $publisher = Publisher::where('url', 'like', '%' . $url . '%')->get();

            //If duplicates has both Yes and No in inc_article field
            if (in_array('Yes', $publisher->pluck('inc_article')->toArray()) && in_array('No', $publisher->pluck('inc_article')->toArray())) {
                //Add 15 to price if inc_article is YES
                $publisher->map(function ($item, $key) use ($publisher) {
                    if ($item->inc_article == 'Yes') {
                        $item->price += 15;
                    }

                    return $item;
                });

                $bestPrice = $publisher->where('price', '!=', null)->sortBy('price')->first();

                //If 2 or more URLs has best price
                if ($publisher->where('price', $bestPrice->price)->count() > 1) {
                    //If 1 of best price's inc_article is YES
                    if (in_array('Yes', $publisher->where('price', $bestPrice->price)->pluck('inc_article')->toArray())) {
                        $bestPrice = $publisher->where('price', $bestPrice->price)->where('inc_article', 'Yes')->sortBy('created_at')->first();
                    } else {
                        $bestPrice = $publisher->where('price', $bestPrice->price)->sortBy('created_at')->first();
                    }

                    //Filter out best price to other URLs
                    $invalidIds = $publisher->whereNotIn('id', [$bestPrice->id])->pluck('id');

                    //Set non-best price to invalid
                    Publisher::whereIn('id', $invalidIds)->update([
                        'valid' => 'invalid'
                    ]);

                    //If best priced URL is not valid, update..
                    if ($bestPrice->valid != 'valid') {
                        Publisher::where('id', $bestPrice->id)->update([
                            'valid' => 'valid'
                        ]);
                    }
                } else {
                    //Filter out best price to other URLs
                    $invalidIds = $publisher->whereNotIn('id', [$bestPrice->id])->pluck('id');

                    //Set non-best price to invalid
                    Publisher::whereIn('id', $invalidIds)->update([
                        'valid' => 'invalid'
                    ]);

                    //If best priced URL is not valid, update..
                    if ($bestPrice->valid != 'valid') {
                        Publisher::where('id', $bestPrice->id)->update([
                            'valid' => 'valid'
                        ]);
                    }
                }
            } else {
                //Get best price among URLs
                $bestPrice = $publisher->where('price', '!=', null)->sortBy('price')->first();
                //Filter out best price to other URLs
                $invalidIds = $publisher->whereNotIn('id', [$bestPrice->id])->pluck('id');

                //Set non-best price to invalid
                Publisher::whereIn('id', $invalidIds)->update([
                    'valid' => 'invalid'
                ]);

                //If best priced URL is not valid, update..
                if ($bestPrice->valid != 'valid') {
                    Publisher::where('id', $bestPrice->id)->update([
                        'valid' => 'valid'
                    ]);
                }
            }
        }
    }

    public function fail($exception = null)
    {
        \Log::error($exception);
    }
}
