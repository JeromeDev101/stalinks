<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\UserRepositoryInterface;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected $model;

    /**
     * UserRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUser(bool $isFullList, $perPage, array $filters)
    {
        if ($isFullList === true) {
            $results = $this->model->isOurs()->select('id', 'name', 'email', 'phone', 'role_id', 'type', 'status', 'id_payment_type', 'host_work_mail', 'work_mail', 'work_mail_pass')->with('role')->get();
            return [
                'data' => $results,
                'total' => $results->count()
            ];
        }

        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $queryBuilder = $queryBuilder->select('id', 'name', 'email', 'phone', 'role_id', 'type', 'status', 'id_payment_type', 'host_work_mail', 'work_mail', 'work_mail_pass')->with(['role' => function($query) {
            $query->select(['id', 'name']);
        }]);

        return $queryBuilder->orderByDesc('id')->paginate($perPage);
    }

    public function showInfo($id)
    {
        $user = $this->model->with('role', 'countriesAccessable', 'internalDomainsAccessable.country', 'backlinks.intDomain.country')->findOrFail($id);
        $user->isAdmin = $user->isAdmin();
        return $user;
    }

    public function hasCountryPermission($userId, $countryId)
    {
        $user = $this->model->where('id', $userId)->with(['countriesAccessable' => function($query) use ($countryId) {
            $query->where('id', $countryId);
        }])->first();

        if (!$user) return false;
        foreach($user->countriesAccessable as $country) {
            if ($country->id === $countryId) return true;
        }

        return false;
    }


}
