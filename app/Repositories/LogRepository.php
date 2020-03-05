<?php


namespace App\Repositories;

use App\Models\Log;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\LogRepositoryInterface;

class LogRepository extends BaseRepository implements LogRepositoryInterface
{
    protected $model;

    /**
     * LogRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Log $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage, $userEmail, $filters)
    {
        $queryBuilder = $this->getQueryBuilderList($perPage, $userEmail, $filters);
        $data = $queryBuilder->paginate($perPage);
        $actionCreateCount = $this->getQueryBuilderList($perPage, $userEmail, $filters)->where('action', config('constant.ACTION_CREATE'))->count();
        $actionUpdateCount = $this->getQueryBuilderList($perPage, $userEmail, $filters)->where('action', config('constant.ACTION_UPDATE'))->count();
        $actionDeleteCount = $this->getQueryBuilderList($perPage, $userEmail, $filters)->where('action', config('constant.ACTION_DELETE'))->count();
        $actionAlexaCount = $this->getQueryBuilderList($perPage, $userEmail, $filters)->where('action', config('constant.ACTION_ALEXA'))->count();

        $counter = [
            config('constant.ACTION_CREATE') => $actionCreateCount,
            config('constant.ACTION_UPDATE') => $actionUpdateCount,
            config('constant.ACTION_DELETE') => $actionDeleteCount,
            config('constant.ACTION_ALEXA') => $actionAlexaCount
        ];

        return ['paginate' => $data, 'counter' => $counter];
    }

    private function getQueryBuilderList($perPage, $userEmail, $filters) {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $queryBuilder = $queryBuilder->select('id', 'user_id', 'table', 'action', 'created_at');
        if ($userEmail !== '') {
            $queryBuilder = $queryBuilder->whereHas('user', function($query) use ($userEmail) {
                $query->where('email', $userEmail);
            });
        }

        $queryBuilder = $queryBuilder->with(['user' => function($query) {
            $query->select(['id', 'email']);
        }])->orderBy('id', 'desc');

        return $queryBuilder;
    }
}
