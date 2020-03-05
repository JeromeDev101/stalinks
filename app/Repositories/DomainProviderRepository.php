<?php

namespace App\Repositories;

use Auth;
use App\Models\DomainProvider;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\DomainProviderRepositoryInterface;


class DomainProviderRepository extends BaseRepository implements DomainProviderRepositoryInterface
{

    protected $model;

    /**
     * DomainProviderRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(DomainProvider $model)
    {
        parent::__construct($model);
    }

    public function getDomainProvider($countryIds, $intDomainIds, bool $isFullDataAcessable, $querySearch)
    {
        $domains = Auth::user()->domains;
        $userId = [];

        foreach ($domains as $domain) {
            $userId[] = $domain->id;
        }

        $queryBuilder = $this->model->whereHas('internalDomains', function($query) use($countryIds, $intDomainIds) {
            $query->whereIn('country_id', $countryIds)
                ->orWhereIn('id', $intDomainIds)
                ->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        })->orWhereIn('id', $userId)->with(['internalDomains' => function($query) use($countryIds, $intDomainIds) {
            $query->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        }])->orderBy('id', 'desc');

        if($querySearch != null) {
            $queryBuilder->where('name' ,'like', '%' . $querySearch . '%')->orWhere('username' ,'like', '%' . $querySearch . '%');
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

    public function showDetail($id, $countryIds, $intDomainIds) {
        $checkPermission = $this->model->whereHas('internalDomains', function($query) use($countryIds, $intDomainIds) {
            $query->whereIn('country_id', $countryIds)
                ->orWhereIn('id', $intDomainIds)
                ->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        })->with(['internalDomains' => function($query) use($countryIds, $intDomainIds) {
            $query->with(['hostingProvider', 'domainProvider', 'user', 'country', 'backlinks']);
        }])->orWhere('id', $id)->count();

        if ($checkPermission === 0) {
            return false;
        }

        return $this->model->findOrFail($id);
    }
}
