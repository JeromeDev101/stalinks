<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface IntDomainRepositoryInterface extends RepositoryInterface
{
    public function fillterIntDomain($atribute, $intDomainIds);

    public function getIntDomain();

    public function paginate($page, $perPage, $filter, $isFullList, $countryIds, $countryExceptIds, $intDomainIds, $allInt);

    public function paginateFormat($page, $perPage, $filter, $isFullList, $countryIds, $countryExceptIds, $intDomainIds, $allInt);

    public function findIntByHosting($countryIds, $hostingID);

    public function findIntByDomain($countryIds, $hostingID);
}
