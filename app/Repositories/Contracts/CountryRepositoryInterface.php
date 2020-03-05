<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface CountryRepositoryInterface extends RepositoryInterface
{
    public function filterCountry($userId, $forExt = false);

    public function validCountryIdList($countryIds, $userId, $forExt = false);

    public function validCountryIdListAllList($countryIds, $userId, $forExt = false);

    public function validCountryIdListOnlyUser($countryIds, $userId, $forExt = false);

    public function getListCountriesAccess($userId, $forExt = false);

    public function getListCountriesModelAccess($userId, $additionExtIds = [], $forExt = false);

    public function findCountryIdList($countryIds, $userId,  $forExt = false);

    public function paginate($page, $perPage, $filter);
}
