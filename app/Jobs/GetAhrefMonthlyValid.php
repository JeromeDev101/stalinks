<?php

namespace App\Jobs;

use App\Models\Publisher;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\ConfigRepositoryInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;

class GetAhrefMonthlyValid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $publisherRepository;
    protected $configRepository;

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
    public function handle(PublisherRepositoryInterface $publisherRepository, ConfigRepositoryInterface $configRepository)
    {
        $configs = $configRepository->getConfigs('ahrefs');

        // get the valid url ids
        Publisher::select(
            'publisher.id',
            'publisher.url'
            )
            ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
            ->leftJoin('registration', 'A.email', '=', 'registration.email')
            ->where('publisher.valid', 'valid')
            ->where('publisher.qc_validation', 'yes')
            ->whereNotNull('publisher.href_fetched_at')
            ->where('registration.account_validation', 'valid')
            ->chunk(500, function ($publishers) use ($configs, $publisherRepository) {
                $listIds = $publishers->pluck('id')->toArray();

                $publisherRepository->getAhrefs($listIds, $configs);
            });
    }
}
