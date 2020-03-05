<?php


namespace App\Repositories;


use App\Models\Config;
use App\Repositories\Contracts\ConfigRepositoryInterface;

class ConfigRepository extends BaseRepository implements ConfigRepositoryInterface
{
    protected $model;

    /**
     * ConfigRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Config $model)
    {
        parent::__construct($model);
    }

    public function getConfigs($type)
    {
        $data = $this->model->where('type', $type)->get();

        $dataReturn = [];

        foreach($data as $item) {
            $dataReturn[$item->code] = $item->value;
        }

        return $dataReturn;
    }


}