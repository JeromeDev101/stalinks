<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function updateAccount($data);
}
