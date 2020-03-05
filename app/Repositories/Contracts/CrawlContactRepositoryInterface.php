<?php
namespace App\Repositories\Contracts;

use App\Models\ExtDomain;
use Illuminate\Database\Eloquent\Collection;

interface CrawlContactRepositoryInterface
{
    public function crawl(ExtDomain $extDomain);
    public function crawls(Collection $extDomains);
}