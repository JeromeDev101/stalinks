<?php

namespace App\Jobs;

use App\BestPriceGenerator;
use App\Events\BestPriceGenerationStart;
use App\Models\Publisher;
use App\Repositories\PublisherRepository;
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

    protected $publisher;

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
            'status' => 'start'
        ]);

        $this->publisher = new PublisherRepository();

        $this->generateBestPriceStart();

        $duplicates = $this->publisher->getDuplicateUrls();

        //Loop through each duplicate url
        foreach ($duplicates as $url) {
            $url = $this->cleanUrl($url);
            $publisher = $this->publisher->getPublisherByUrl($url, ['user.registration']);
            $publisherIncArticles = $publisher->pluck('inc_article')->toArray();

            //If duplicates has both Yes and No in inc_article field
            if ($this->urlIncArticleHasYesOrNo($publisherIncArticles)) {
                //Add 15 to price if inc_article is NO
                $publisher->map(function ($item, $key) use ($publisher) {
                    if (strtolower($item->inc_article) == 'no' && $item->price) {
                        $item->price += 15;
                    }

                    return $item;
                });

                $bestPrice = $publisher
                    ->where('price', '!=', null)
                    ->where('user', '!=', null)
                    ->where('qc_validation', '!=', 'no')
                    ->where('user.registration.account_validation', '!=', 'invalid')
                    ->sortBy('price')->first();

                if ($bestPrice) {
                    //If 2 or more URLs has best price
                    if ($publisher->where('price', $bestPrice->price)->count() > 1) {
                        $publisherIncArticlesByPrice = $publisher
                            ->where('price', $bestPrice->price)
                            ->pluck('inc_article')->toArray();

                        //If 1 of best price's inc_article is YES, set that as Best Price
                        if ($this->oneUrlHasArticle($publisherIncArticlesByPrice)) {
                            $bestPrice = $publisher
                                ->where('price', $bestPrice->price)
                                ->where('user.registration.account_validation', '!=', 'invalid')
                                ->where('inc_article', 'Yes')
                                ->sortBy('created_at')
                                ->first();
                        } else {
                            $bestPrice = $publisher
                                ->where('price', $bestPrice->price)
                                ->where('user.registration.account_validation', '!=', 'invalid')
                                ->sortBy('created_at')
                                ->first();
                        }

                        if ($bestPrice) {
                            //Filter out best price to other URLs
                            $invalidIds = $publisher
                                ->whereNotIn('id', [$bestPrice->id])
                                ->pluck('id');

                            //Set non-best price to invalid
                            Publisher::whereIn('id', $invalidIds)->update([
                                'valid' => 'invalid'
                            ]);

                            Publisher::where('id', $bestPrice->id)->update([
                                'valid' => 'valid'
                            ]);
                        }
                    } else {
                        //Filter out best price to other URLs
                        $invalidIds = $publisher
                            ->whereNotIn('id', [$bestPrice->id])
                            ->pluck('id');

                        //Set non-best price to invalid
                        Publisher::whereIn('id', $invalidIds)->update([
                            'valid' => 'invalid'
                        ]);

                        Publisher::where('id', $bestPrice->id)->update([
                            'valid' => 'valid'
                        ]);
                    }
                }
            } else {
                if ($publisher) {
                    //Get best price among URLs
                    $bestPrice = $publisher
                        ->where('price', '!=', null)
                        ->where('user', '!=', null)
                        ->where('qc_validation', '!=', 'no')
                        ->where('user.registration.account_validation', '!=', 'invalid')
                        ->sortBy('price')->first();

                    if ($bestPrice) {
                        //Filter out best price to other URLs
                        $invalidIds = $publisher
                            ->whereNotIn('id', [$bestPrice->id])
                            ->pluck('id');

                        //Set non-best price to invalid
                        Publisher::whereIn('id', $invalidIds)->update([
                            'valid' => 'invalid'
                        ]);

                        Publisher::where('id', $bestPrice->id)->update([
                            'valid' => 'valid'
                        ]);
                    }
                }
            }
        }

        $nonDuplicates = $this->publisher->getNonDuplicateUrls();

        foreach ($nonDuplicates as $url) {
            $publisher = Publisher::where('url', $url)->first();

            if ($publisher &&
                $publisher->valid == 'invalid' &&
                $publisher->user &&
                $publisher->user->registration &&
                $publisher->user->registration->account_validation == 'valid') {
                $publisher->update([
                    'valid' => 'valid'
                ]);
            }

            if (!$publisher || !$publisher->user || !$publisher->user->registration) {
                continue;
            }

            if (in_array_custom($publisher->user->registration->account_validation, [
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
            'status' => 'end'
        ]);

        //Notify buyers
        $this->generateBestPriceEnd();

        //Notify admin
        $this->generateBestPricesDoneNotification($this->userId);
    }

    public function fail($exception = null)
    {
        BestPriceGenerator::create([
            'user_id' => $this->userId,
            'status' => 'end'
        ]);

        //Notify buyers
        $this->generateBestPriceEnd();

        //Notify admin
        $this->generateBestPricesDoneNotification($this->userId);

        \Log::error($exception);
    }

    private function cleanUrl($url)
    {
        return preg_replace('/\s+/', '', $url);
    }

    private function urlIncArticleHasYesOrNo(array $urls)
    {
        return in_array_custom('yes', $urls) && in_array_custom('no', $urls);
    }

    private function oneUrlHasArticle(array $urls)
    {
        return in_array_custom('yes', $urls);
    }
}
