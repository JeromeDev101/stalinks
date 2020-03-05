<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface MailLogRepositoryInterface extends RepositoryInterface
{
    public function paginate($perPage, $userEmail, $filters);
}