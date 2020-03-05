<?php

namespace App\Repositories;

use App\Models\Backlink;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use Illuminate\Support\Arr;


class BackLinkRepository extends BaseRepository implements BackLinkRepositoryInterface
{

    protected $model;

    /**
     * BackLinkRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Backlink $model)
    {
        parent::__construct($model);
    }

    public function fillterBackLink($countryIds, $intDomainIds, $status)
    {
        if (count($status) === 1 && $status[0] == 0) $status = [];

        $queryBuilder = $this->model->selectRaw('countries.id as cid, countries.name as cname, countries.code as ccode,
            int_domains.country_id as country_id, backlinks.status as status, count(backlinks.id) as total')
            ->join('int_domains', 'int_domains.id', '=', 'backlinks.int_domain_id')
            ->join('countries', 'int_domains.country_id', '=', 'countries.id')
            ->whereIn('int_domains.country_id', $countryIds)
            ->orWhereIn('int_domains.id', $intDomainIds)
            ->groupBy('int_domains.country_id', 'backlinks.status');

        $data = $queryBuilder->get();
        $sumTotal = 0;
        $dataReturn = [];
        $statusList = config('constant.backlink_status');

        foreach($data as $item) {
            $sumTotal += $item->total;
            $key = $item->country_id;

            if (!in_array($item->status, $statusList)) {
                $item->status = $statusList['BACKLINK_STATUS_UNDEFINED'];
            }

            if (!isset($dataReturn[$key])) {
                $dataReturn[$key] = ['country' => ['id' => $item->cid, 'name' => $item->cname, 'code' => $item->ccode]];
                foreach ($statusList as $value) {
                    $dataReturn[$key][$value] = 0;
                }
            }

            $dataReturn[$key][$item->status] += $item->total;
        }
        return [
            'total' => $sumTotal,
            'data' => Arr::divide($dataReturn)[1]
        ];
    }

    public function fillterPrice($countryIds, $intDomainIds)
    {
        $data = $this->model->selectRaw('countries.id as cid, countries.name as cname, countries.code as ccode,
            int_domains.country_id as country_id, sum(backlinks.price) as total')
            ->join('int_domains', 'int_domains.id', '=', 'backlinks.int_domain_id')
            ->join('countries', 'int_domains.country_id', '=', 'countries.id')
            ->whereIn('int_domains.country_id', $countryIds)
            ->orWhereIn('int_domains.id', $intDomainIds)
            ->groupBy('int_domains.country_id')->get();

        $sumTotal = 0;
        $dataReturn = [];

        foreach($data as $item) {
            $sumTotal += $item->total;
            $key = $item->country_id;

            if (!isset($dataReturn[$key])) {
                $dataReturn[$key] = ['country' => ['id' => $item->cid, 'name' => $item->cname, 'code' => $item->ccode]];
            }

            $dataReturn[$key]['total'] = $item->total;
        }
        
        return [
            'total' => $sumTotal,
            'data' => Arr::divide($dataReturn)[1]
        ];
    }

    public function getBackLink($countryIds, $intDomains, $filters)
    {
        $query = $this->model->whereHas('intDomain', function ($query) use ($countryIds, $intDomains, $filters) {
            if (isset($filters->int_id) && $filters->int_id > 0) {
                $query->where('id', $filters->int_id)->where(function($queryIn) use ($countryIds, $intDomains) {
                    $queryIn->whereIn('country_id', $countryIds)->orWhereIn('id', $intDomains);
                });
            } else {
                $query->whereIn('country_id', $countryIds)->orWhereIn('id', $intDomains);
            }
        })->orderBy('id', 'desc');
        $backlink = $this->fillter($query, $filters);

        if ($filters->full_data === true) {
            $data = $backlink->with('intDomain', 'extDomain', 'user')->get();

            return [
                'data' => $data,
                'total' => $data->count(),
            ];
        }

        return $backlink->with('intDomain', 'extDomain', 'user')->paginate(config('common.paginate.default'));
    }

    protected function fillter($query, $filters)
    {
        if (!empty($filters->querySearch)) {
            $query = $this->model
            ->whereHas('extDomain', function ($query) use ($filters) {
                $query->where('domain', 'like', '%' . $filters->querySearch . '%');
            })
            ->orWhereHas('intDomain', function ($query) use ($filters) {
                $query->where('domain', 'like', '%' . $filters->querySearch . '%');
            });
        }

        return $query;
    }

    public function getExtIdsFromInt($intDomainIds)
    {
        $backLinks = $this->model->whereIn('int_domain_id', $intDomainIds)->get();
        $extDomainsIds = [];

        foreach($backLinks as $item) {
            $extDomainsIds[] = $item->ext_domain_id;
        }

        return $extDomainsIds;
    }


}
