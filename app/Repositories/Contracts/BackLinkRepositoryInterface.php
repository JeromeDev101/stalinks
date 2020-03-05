<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface BackLinkRepositoryInterface extends RepositoryInterface
{
    public function fillterBackLink($countryIds, $intDomainIds, $status);

    public function fillterPrice($countryIds, $intDomainIds);

    public function getBackLink($countryIds, $intDomains, $fillter);

    public function getExtIdsFromInt($intDomainIds);
}
