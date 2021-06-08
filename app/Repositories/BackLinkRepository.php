<?php

namespace App\Repositories;

use App\Models\Backlink;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
        $paginate = $filters->paginate == '' ? 50:$filters->paginate;
        $user = Auth::user();
        $user_id = $user->id;
        $query = $this->model->orderBy('id', 'desc');

        if(isset($filters->sub_buyer_id) && $filters->sub_buyer_id != '') {
            $query->where('user_id', $filters->sub_buyer_id);
        }

        if ( isset($user->registration->is_sub_account) && $user->registration->is_sub_account == 1 ) {
            if ( isset($user->registration->team_in_charge) ) {
                $user_model = User::where('id', $user->registration->team_in_charge)->first();
                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        if((isset($user->registration->type) && $user->registration->type == 'Buyer') || $user->role_id == 5){
            if($filters->sub_buyer_id == '') {
                // $query->where('user_id', $user_id)
                //     ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
                //         return $query->orWhereIn('user_id', $sub_buyer_ids);
                //     });

                $query->where(function($query) use ($sub_buyer_ids, $user_id){
                        $UserId[] = $user_id;
                        if(count($sub_buyer_ids) > 0) {
                            return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
                        } else{
                            return $query->whereIn('user_id', $UserId);
                        }
                    })
            }
        }

        $backlink = $this->filter($query, $filters);

        if ($filters->full_data === true) {
            $data = $backlink->with(['publisher' => function($query) {
                                $query->with('user:id,name,username');
                            }, 'user'])
                            ->with('article')
                            ->get();

            return [
                'data' => $data,
                'total' => $data->count(),
            ];
        }

        if( $paginate == 'All' ){
            $data = $backlink->with('article')->with(['publisher' => function($query){ $query->with('user:id,name,username'); }, 'user'])->get();
            return [
                'data' => $data,
                'total' => $data->count(),
            ];
        }else{
            return $backlink->with('article')->with(['publisher' => function($query){ $query->with('user:id,name,username'); }, 'user'])->paginate($paginate);
        }

    }

    protected function filter($query, $filters)
    {
        if (!empty($filters->querySearch)) {
            $query = $this->model
            ->whereHas('publisher', function ($query) use ($filters) {
                $query->where('url', 'like', '%' . $filters->querySearch . '%');
            });
        }

        if(!empty($filters->url_advertiser)) {
            $query = $query->where('url_advertiser', 'like', '%' . $filters->url_advertiser . '%');
        }

        if(!empty($filters->seller)) {
            $query = $query->whereHas('publisher', function ($query) use ($filters) {
                $query->where('user_id', $filters->seller );
            });
        }

        if( !empty($filters->buyer) && $filters->buyer != ""){
            $query = $query->where('user_id', $filters->buyer);
        }

        if( !empty($filters->status) && $filters->status != ""){
            if (!is_array($filters->status)) {
                $query = $query->where('status', $filters->status);
            } else {
                $query = $query->whereIn('status', $filters->status);
            }
        }

        if( !empty($filters->backlink_id) && $filters->backlink_id != ""){
            $query = $query->where('id', $filters->backlink_id);
        }

        if (!empty($filters->process_date) && $filters->process_date->startDate != null) {
            $query->where('date_process', '>=', Carbon::create($filters->process_date->startDate)->format('Y-m-d'));
            $query->where('date_process', '<=', Carbon::create($filters->process_date->endDate)->format('Y-m-d'));
        }

        if (!empty($filters->date_completed) && $filters->date_completed->startDate != null) {
            $query->where('live_date', '>=', Carbon::create($filters->date_completed->startDate)->format('Y-m-d'));
            $query->where('live_date', '<=', Carbon::create($filters->date_completed->endDate)->format('Y-m-d'));
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
