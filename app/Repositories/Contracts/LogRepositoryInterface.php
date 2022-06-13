<?php


namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface LogRepositoryInterface extends RepositoryInterface
{
    public function paginate($perPage, $userEmail, $filters, $request);
}
