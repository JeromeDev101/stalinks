<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface PublisherRepositoryInterface extends RepositoryInterface
{
    public function getList();
    public function importExcel($file);
}
