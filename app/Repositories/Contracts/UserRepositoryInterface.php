<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function getUser(bool $isFullList, $perPage, array $filters);

    public function showInfo($id);

    public function hasCountryPermission($userId, $countryId);
}
