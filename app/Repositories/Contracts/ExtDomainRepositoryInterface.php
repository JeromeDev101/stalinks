<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface ExtDomainRepositoryInterface extends RepositoryInterface
{
    public function fillterExtDomain($status, $countryIds, $countryIdsInt, $extDomainAdditionIds = []);

    public function importAlexaSites($data, $total, $countryCode, $start, $count);

    public function importExcel($file);

    public function getContacts($listIdExtDomains);

    public function paginate($input);

    public function updateStatus($listIds, $newStatus, $countryIdsFilter);

    public function crawlContact($listIds, $pushToQueue);

    public function getAhrefs($listIds, $configs);
}
