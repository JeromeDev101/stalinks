<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface HostingRepositoryInterface extends RepositoryInterface
{

    public function getHosting($countryIds, $intDomainsIdAddition, bool $isFullDataAcessable, $querySearch);

    public function showDetail($id, $countryIds, $intDomainsIdAddition);
}
