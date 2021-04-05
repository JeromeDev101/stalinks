<?php


namespace App\Repositories;


use App\Models\Log;
use App\Repositories\Contracts\query;
use App\Repositories\Contracts\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

abstract class BaseLogRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * get Model
     *
     * @return Model
     */
    public function model()
    {
        return get_class($this->model);
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes)
    {
        $this->addLog(config('constant.ACTION_CREATE'), ['attr' => $attributes, 'method' => 'create']);
    }

    /**
     * @inheritdoc
     */
    public function update(Model $model, array $attributes = [])
    {
        if ($this->isDirty($model, $attributes)) {
            $this->addLog(config('constant.ACTION_UPDATE'), ['model' => $model, 'attr' => $attributes, 'method' => 'update']);
        }
    }


    /**
     * @inheritdoc
     */
    public function save(Model $model)
    {
        $this->addLog(config('constant.ACTION_CREATE'), ['model' => $model, 'method' => 'save']);
    }

    /**
     * @inheritdoc
     */
    public function delete(Model $model)
    {
        $this->addLog(config('constant.ACTION_DELETE'), ['model' => $model, 'method' => 'delete']);
    }

    /**
     * @inheritdoc
     */
    public function destroy(array $ids)
    {
        $this->addLog(config('constant.ACTION_DELETE'), ['ids' => $ids]);
    }

    public function updateMultiple(Builder $query, array $attributes = [])
    {
        $this->addLog(config('constant.ACTION_UPDATE'), ['attr' => $attributes, 'method' => 'updateMultiple']);
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        $this->addLog(config('constant.ACTION_UPDATE'), ['attr' => $attributes, 'method' => 'updateOrCreate']);
    }

    public function firstOrCreate(array $attributes)
    {
        $this->addLog(config('constant.ACTION_CREATE'), ['attr' => $attributes, 'method' => 'firstOrCreate']);
    }


    protected function addLog(string $action, $payload = []) {
//        $payload['is_admin'] = optional(Auth::user())->isAdmin();

//        Log::create([
//            'table' => $this->model(),
//            'action' => $action,
//            'user_id' => Auth::id(),
//            'payload' => json_encode($payload)
//        ]);
    }

    protected function isDirty(Model $model,  array $attributes = []) {
        foreach ($attributes as $column => $value) {
            if ($column === 'id') continue;

            $model->$column = $value;
        }

        return $model->isDirty();
    }
}
