<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use Illuminate\Database\Eloquent\Model;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface {
    protected $extDomain;

    public function __construct(ExtDomain $model)
    {
        parent::__construct($model);
    }

    public function getList()
    {
        $list = ExtDomain::where('status', 100);
        return [
            "data" => $list->get(),
            "total" => $list->count()
        ];
    }
}