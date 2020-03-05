<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface ExtDomainRepositoryInterface extends RepositoryInterface
{
    public function fillterExtDomain($status, $countryIds, $countryIdsInt, $extDomainAdditionIds = []);

    public function importAlexaSites($data, $countryCode, $start, $count);

    public function getContacts($listIdExtDomains);

    public function paginate($page, $perPage, $filter, $countriesId, $countriesIdInt, $countriesExceptIds, $allExtFilter, $sort, $extDomainAdditionIds = []);

    public function updateStatus($listIds, $newStatus, $countryIdsFilter);

    public function crawlContact($listIds, $pushToQueue);

    public function getAhrefs($listIds, $configs);
}
