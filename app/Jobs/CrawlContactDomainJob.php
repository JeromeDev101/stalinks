<?php

namespace App\Jobs;

use App\Models\ExtDomain;
use App\Repositories\Contracts\CrawlContactRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class CrawlContactDomainJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $extDomains;

    /**
     * Create a new job instance.
     *
     * @param Collection $extDomains
     * @return void
     */
    public function __construct(Collection $extDomains)
    {
        $this->extDomains = $extDomains;
    }

    /**
     * Execute the job.
     *
     * @param CrawlContactRepositoryInterface $crawler
     * @return void
     */
    public function handle(CrawlContactRepositoryInterface $crawler)
    {
        $sizeData = $this->extDomains->count();
        if (!isset($this->extDomains) || $sizeData == 0) {
            return;
        }

        $domainList = [];
        $listId = [];
        foreach($this->extDomains as $extDomain) {
            $domainList[] = ['id' => $extDomain->id, 'domain' => $extDomain->domain];
            $listId[] = $extDomain->id;
        }

        $jobId = join("-", $listId);
        Log::info('CrawlContactDomainJob start', ['count' => $sizeData, 'job_id' => $jobId, 'data' => $domainList]);
        $data = $crawler->crawls($this->extDomains);
        Log::info('CrawlContactDomainJob done', ['count' => $sizeData, 'job_id' => $jobId, 'status' => $data]);
    }
}
