<?php


namespace App\Repositories;

use Auth;
use App\Models\MailLog;
use App\Repositories\Contracts\MailLogRepositoryInterface;

class MailLogRepository extends BaseRepository implements MailLogRepositoryInterface
{
    protected $model;

    /**
     * LogRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(MailLog $model)
    {
        parent::__construct($model);
    }

    public function paginate($perPage, $userEmail, $filters)
    {
        $queryBuilder = $this->getQueryBuilderList($perPage, $userEmail, $filters);
        $data = $queryBuilder->paginate($perPage);
        $successCount = $this->getQueryBuilderList($perPage, $userEmail, $filters)->where('status', config('constant.status_mails.SUCCESS'))->count();

        $counter = [
            'success' => $successCount,
        ];

        return ['paginate' => $data, 'counter' => $counter];
    }

    private function getQueryBuilderList($perPage, $userEmail, $filters) {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $queryBuilder = $queryBuilder->select('id', 'user_id', 'from', 'to', 'status', 'ext_domain', 'title', 'content', 'created_at');
        if ($userEmail !== '') {
            $queryBuilder = $queryBuilder->whereHas('user', function($query) use ($userEmail) {
                $query->where('email', $userEmail);
            });
        }

        $queryBuilder = $queryBuilder->with(['user' => function($query) {
            $query->select(['id', 'email']);
        }])->orderBy('id', 'desc');

        if (Auth::user()->type == config('constant.USER_TYPE_ADMIN')) {
            return $queryBuilder;
        } 

        return $queryBuilder->where('user_id', Auth::id());
    }

}