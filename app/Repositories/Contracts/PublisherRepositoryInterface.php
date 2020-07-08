<?php

namespace App\Repositories\Contracts;

use App\Repositories\Contracts\RepositoryInterface;

interface PublisherRepositoryInterface extends RepositoryInterface
{
    public function getList($filter);

    public function importExcel($file);

    public function getAhrefs($listIds, $configs);
    public function getPublisherSummary($user_id);
}
