<?php

namespace App\Repositories;
use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    protected $model;

    /**
     * RoleRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
