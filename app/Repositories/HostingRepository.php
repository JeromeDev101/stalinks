<?php

namespace App\Repositories;

use Auth;
use App\Models\HostingProvider;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\HostingRepositoryInterface;


class HostingRepository extends BaseRepository implements HostingRepositoryInterface
{

    protected $model;

    /**
     * HostingRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(HostingProvider $model)
    {
        parent::__construct($model);
    }

    public function getHosting($countryIds, $intDomainsIdAddition, bool $isFullDataAcessable, $querySearch)
    {
        $userId = $this->getHostingId();
        $queryBuilder = $this->model->whereHas('internalDomains', function($query) use($countryIds, $intDomainsIdAddition, $userId) {
            $query->whereIn('country_id', $countryIds)
                ->orWhereIn('id', $intDomainsIdAddition)
            ->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        })->orWhereIn('id', $userId)->with(['internalDomains' => function($query) {
            $query->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        }])->orderBy('id', 'desc');

        if($querySearch != null) {
            $queryBuilder->where('name' ,'like', '%' . $querySearch . '%')->orWhere('username' ,'like', '%' . $querySearch . '%')->orWhere('link' ,'like', '%' . $querySearch . '%');
        }

        if ($isFullDataAcessable === true) {
            $data = $queryBuilder->get();
            return [
                'data' => $data,
                'total' => $data->count()
            ];
        }

        return $queryBuilder->paginate(config('common.paginate.default'));
    }

    public function showDetail($id, $countryIds, $intDomainsIdAddition) {
        $checkPermission =  $this->model->whereHas('internalDomains', function($query) use($countryIds, $intDomainsIdAddition) {
            $query->whereIn('country_id', $countryIds)
                ->orWhereIn('id', $intDomainsIdAddition);
        })->orWhere('id', $id)->count();

        if ($checkPermission === 0) {
            return false;
        }

        return $this->model->findOrFail($id);
    }

    private function getHostingId()
    {
        $hostings = Auth::user()->hostings;
        $userId = [];
        foreach ($hostings as $hosting) {
            $userId[] = $hosting->id;
        }

        return $userId;
    }
}
