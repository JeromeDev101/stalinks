<?php


namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface ConfigRepositoryInterface extends RepositoryInterface
{
    public function getConfigs($type);
}