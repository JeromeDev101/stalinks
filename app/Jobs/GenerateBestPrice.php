<?php

namespace App\Jobs;

use App\BestPriceGenerator;
use App\Events\BestPriceGenerationStart;
use App\Models\Publisher;
use App\Repositories\Traits\NotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateBestPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationTrait;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BestPriceGenerator::create([
            'user_id' => $this->userId,
            'status'  => 'start'
        ]);

        $this->generateBestPriceStart();

        $duplicates = Publisher::select('url')->groupBy('url')->havingRaw('count(*) > 1')->get()->pluck('url');

        //Loop through each duplicate url
        foreach ($duplicates as $url) {
            $url       = preg_replace('/\s+/', '', $url);
            $publisher = Publisher::with('user.registration')->where('url', 'like', '%' . $url . '%')->get();

            //If duplicates has both Yes and No in inc_article field
            if (in_array_custom('yes', $publisher->pluck('inc_article')->toArray()) && in_array_custom('no', $publisher->pluck('inc_article')->toArray())) {
                //Add 15 to price if inc_article is NO
                $publisher->map(function ($item, $key) use ($publisher) {
                    if ($item->inc_article == 'no') {
                        $item->price += 15;
                    }

                    return $item;
                });

                $bestPrice = $publisher->where('price', '!=', null)->where('user.registration.account_validation', '!=', 'invalid')->sortBy('price')->first();

                if ($bestPrice) {
                    //If 2 or more URLs has best price
                    if ($publisher->where('price', $bestPrice->price)->count() > 1) {
                        //If 1 of best price's inc_article is YES, set that as Best Price
                        if (in_array_custom('yes', $publisher->where('price', $bestPrice->price)->pluck('inc_article')->toArray())) {
                            $bestPrice = $publisher->where('price', $bestPrice->price)->where('user.registration.account_validation', '!=', 'invalid')->where('inc_article', 'Yes')->sortBy('created_at')->first();
                        } else {
                            $bestPrice = $publisher->where('price', $bestPrice->price)->where('user.registration.account_validation', '!=', 'invalid')->sortBy('created_at')->first();
                        }

                        if ($bestPrice) {
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
            } else {
                if ($publisher) {
                    //Get best price among URLs
                    $bestPrice = $publisher->where('price', '!=', null)->where('user.registration.account_validation', '!=', 'invalid')->sortBy('price')->first();

                    if ($bestPrice) {
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
        }

        $nonDuplicates = Publisher::select('url')->groupBy('url')->havingRaw('count(*) < 2')->get()->pluck('url');

        foreach ($nonDuplicates as $url) {
            $publisher = Publisher::where('url', $url)->first();

            if ($publisher && $publisher->valid == 'invalid' && $publisher->user && $publisher->user->registration && $publisher->user->registration->account_validation == 'valid') {
                $publisher->update([
                    'valid' => 'valid'
                ]);
            }

            if (!$publisher || !$publisher->user || !$publisher->user->registration) {
                continue;
            }

            if (in_array($publisher->user->registration->account_validation, [
                'valid',
                'processing'
            ])) {
                $publisher->update([
                    'valid' => 'valid'
                ]);
            } else {
                $publisher->update([
                    'valid' => 'invalid'
                ]);
            }
        }

        BestPriceGenerator::create([
            'user_id' => $this->userId,
            'status'  => 'end'
        ]);

        //Notify buyers
        $this->generateBestPriceEnd();

        //Notify admin
        $this->generateBestPricesDoneNotification($this->userId);
    }

    public function fail($exception = null)
    {
        \Log::error($exception);
    }
}
