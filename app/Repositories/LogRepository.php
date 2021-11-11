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
        $queryBuilder = $queryBuilder->select('id', 'user_id', 'table', 'action', 'page', 'created_at');
        if ($userEmail !== '') {
            $queryBuilder = $queryBuilder->whereHas('user', function($query) use ($userEmail) {
                $query->where('email', $userEmail);
            });
        }

        $queryBuilder = $queryBuilder->with(['user' => function($query) {
            $query->select(['id', 'email', 'username']);
        }])->orderBy('id', 'desc');

        return $queryBuilder;
    }

    public function getModels($path){
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $filename = 'App\\Models' . '\\' . $result;
            if (is_dir($filename)) {
                $out = array_merge($out, getModels($filename));
            }else{
                $out[] = substr($filename,0,-4);
            }
        }
        return $out;
    }
}
