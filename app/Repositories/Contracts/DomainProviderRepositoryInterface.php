<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface DomainProviderRepositoryInterface extends RepositoryInterface
{

    public function getDomainProvider($countryIds, $intDomainIds,  bool $isFullDataAcessable, $querySearch);

    public function showDetail($id, $intDomainIds, $countryIds);
}
