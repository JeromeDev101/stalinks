<?php

namespace App\Repositories;

use App\Models\IntDomain;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\IntDomainRepositoryInterface;
use Illuminate\Support\Arr;


class IntDomainRepository extends BaseRepository implements IntDomainRepositoryInterface
{

    protected $model;

    /**
     * IntDomainRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(IntDomain $model)
    {
        parent::__construct($model);
    }

    public function fillterIntDomain($countryIds, $intDomainsIds)
    {
        $data = $this->model->selectRaw('country_id, count(*) as total')
            ->whereIn('country_id', $countryIds)
            ->orWhereIn('id', $intDomainsIds)
            ->with(['country' => function($query) {
                $query->select('id', 'name', 'code');
            }])
            ->groupBy('country_id')
            ->get();

        $sumTotal = 0;

        foreach($data as &$item) {
            $sumTotal += $item->total;
            unset($item->country_id);
        }

        return [
            'total' => $sumTotal,
            'data' => $data
        ];
    }

    public function getIntDomain()
    {
        return $this->model->with('hostingProvider', 'domainProvider', 'user', 'country')->paginate(config('common.paginate.default'));
    }

    public function paginate($page, $perPage, $filters, $isFullList, $countryIds, $countryExceptIds, $intDomainIds, $allInt)
    {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $queryBuilder->orWhere(function($query) use ($intDomainIds, $countryIds, $allInt, $countryExceptIds) {
            $query->whereIn('id', $intDomainIds);
            if ($allInt === false) {
                $query->where(function($queryIn) use ($countryExceptIds, $countryIds) {
                    $queryIn->whereIn('country_id', $countryIds)
                    ->orWhereIn('country_id', $countryExceptIds);
                });
            }
        });

        $queryBuilder = $queryBuilder->with(['country' => function($query) {
            $query->select(['id', 'name', 'code']);
        }, 'backlinks' => function($query) {
            $query->select('int_domain_id', 'price');
        }, 'hostingProvider' => function($query) {
            $query->select('id', 'name', 'link');
        }, 'domainProvider' => function($query) {
            $query->select('id', 'name');
        }, 'user' => function($query) {
            $query->select('id', 'name');
        }]);

        if ($isFullList) {
            return $queryBuilder->orderBy('id', 'desc')->get();
        }

        $data = $queryBuilder->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $page);

        foreach($data as &$item) {
            $item['total_spent'] = collect($item['backlinks'])->sum('price');
            unset($item['backlinks']);
        }

        return $data;
    }

    public function paginateFormat($page, $perPage, $filters, $isFullList, $countryIds, $countryExceptIds, $intDomainIds, $allInt)
    {
        $data = $this->paginate($page, $perPage, $filters, $isFullList, $countryIds, $countryExceptIds, $intDomainIds, $allInt);

        if ($isFullList) {
            return [
                'data' => $data,
                'total' => count($data),
            ];
        }

        return $data;
    }

    public function findIntByHosting($countryIds, $hostingID)
    {
        return $this->model->with('backlinks','country', 'hostingProvider', 'domainProvider', 'user')->where('hosting_provider_id', $hostingID)->whereIn('country_id', $countryIds)->orderBy('id', 'DESC')->paginate(config('common.paginate.default'));
    }

    public function findIntByDomain($countryIds, $hostingID)
    {
        return $this->model->with('backlinks','country', 'hostingProvider', 'domainProvider', 'user')->where('domain_provider_id', $hostingID)->whereIn('country_id', $countryIds)->orderBy('id', 'DESC')->paginate(config('common.paginate.default'));
    }


}
