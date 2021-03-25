<?php

namespace App\Repositories;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Query\Builder;

/**
 * Class BaseRepository
 *
 * @package App\Repositories
 */
class BaseRepository extends BaseLogRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * make new Model
     *
     * @return Model
     */
    public function makeModel()
    {
        $this->model = App::make($this->model());

        return $this->model;
    }
    /**
     * reset model query
     *
     * @return void
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * @inheritdoc
     */
    public function find(array $conditions = [])
    {
        return $this->model->where($conditions)->orderBy('id', 'desc')->get();
    }

    /**
     * @inheritdoc
     */
    public function findOne(array $conditions)
    {
        return $this->model->where($conditions)->first();
    }

    /**
     * @inheritdoc
     */
    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @inheritdoc
     */
    public function create(array $attributes)
    {
        parent::create($attributes);
        return $this->model->create($attributes);
    }

    /**
     * @inheritdoc
     */
    public function update(Model $model, array $attributes = [])
    {
        parent::update($model, $attributes);
        return $model->update($attributes);
    }

    /**
     * @inheritdoc
     */
    public function save(Model $model)
    {
        parent::save($model);
        return $model->save();
    }

    /**
     * @inheritdoc
     */
    public function delete(Model $model)
    {
        parent::delete($model);
        return $model->delete();
    }

    /**
     * @inheritdoc
     */
    public function get($query)
    {
        return $query->get();
    }

    /**
     * @inheritdoc
     */
    public function destroy(array $ids)
    {
        parent::destroy($ids);
        return $this->model->destroy($ids);
    }

    /**
     * @inheritdoc
     */
    public function findCount(array $conditions)
    {
        return $this->model->where($conditions)->count();
    }

    public function toBase($query)
    {
        return $query->toBase();
    }

    public function updateMultiple(Builder $query, array $attributes = [])
    {
        parent::updateMultiple($query, $attributes);
        return $query->update($attributes);
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        parent::updateOrCreate($attributes, $values);
        return $this->model->updateOrCreate($attributes, $values);
    }

    public function firstOrCreate(array $attributes)
    {
        parent::firstOrCreate($attributes);
        return $this->model->firstOrCreate($attributes);
    }


    /**
     * @inheritdoc
     */
    public function findAll($columns = ['*'])
    {
        return $this->model->select($columns)->orderBy('id', 'desc')->get();
    }

    /**
     * @inheritdoc
     */
    public function findByIds(array $ids, $columns = ['*'])
    {
        return $this->model->whereIn('id', $ids)->orderBy('id', 'desc')->get($columns);
    }

    /**
     * @inheritdoc
     */
    public function buildSimpleFilterQuery($filters)
    {
        $queryBuilder = $this->model->newQuery();

        return $this->buildSimpleFilterQueryBuilder($queryBuilder, $filters);
    }

    public function buildSimpleFilterQueryBuilder(&$queryBuilder, $filters, $excepts = [])
    {
        foreach($filters as $operator => $filter) {
            if ($operator == 'where') {
                $queryBuilder = $queryBuilder->where($filter);
            } else if ($operator == 'whereIn') {
                foreach($filter as $filterIn) {
                    if (!$this->isExisted($excepts, $filterIn[0]))
                        $queryBuilder = $queryBuilder->whereIn($filterIn[0], $filterIn[1]);
                }
            } else if ($operator == 'orWhereIn') {
                foreach($filter as $filterIn) {
                    if (!$this->isExisted($excepts, $filterIn[0]))
                        $queryBuilder = $queryBuilder->orWhereIn($filterIn[0], $filterIn[1]);
                }
            } else if ($operator == 'notIn') {
                foreach($filter as $filterNotIn) {
                    if (!$this->isExisted($excepts, $filterNotIn[0]))
                        $queryBuilder = $queryBuilder->whereNotIn($filterNotIn[0], $filterNotIn[1]);
                }
            } else if ($operator == 'orWhere'){
                $queryBuilder = $queryBuilder->orWhere($filter);
            }
        }

        return $queryBuilder;
    }

    private function isExisted($excepts, $field) {
        foreach ($excepts as $column) {
            if ($field === $column) return true;
        }

        return false;
    }
}
