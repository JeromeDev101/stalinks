<?php

namespace App\Services;

use App\Models\ExtDomain;
use App\Models\Publisher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GraphService
{
    public function urlValidationQuery($request)
    {
        $query = Publisher::select(DB::raw('
            CONCAT(MONTHNAME(MAX(created_at)), " ", YEAR(MAX(created_at))) as xaxis,
            COUNT(IF(valid = \'valid\', 1, NULL)) AS valid,
            COUNT(IF(valid = \'invalid\', 1, NULL)) AS invalid,
            COUNT(id) AS total
        '))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'));

        if (isset($request['start_date']) && $request['start_date'] != 'null') {
            $query->where('created_at', '>=', Carbon::create($request['start_date'])->format('Y-m-d'));
            $query->where('created_at', '<=', Carbon::create($request['end_date'])->format('Y-m-d'));
        }

        return $query->get();
    }

    public function urlValidPriceQuery($request)
    {
        if ($request['scope'] == 'global') {
            $query = Publisher::select(DB::raw(
                'CONCAT(MONTHNAME(MAX(publisher.created_at)), " ", YEAR(MAX(publisher.created_at))) AS xaxis,
                        COUNT(publisher.id) AS upload,
                        COUNT(IF(publisher.valid = \'valid\', 1, NULL)) AS valid,
                        COUNT(IF(publisher.price_basis IN (\'good\', \'average\') AND publisher.valid = \'valid\', 1, NULL)) AS quality_price'
            ))
                ->groupBy(DB::raw('MONTH(publisher.created_at)'))
                ->groupBy(DB::raw('YEAR(publisher.created_at)'))
                ->orderBy(DB::raw('YEAR(publisher.created_at)'))
                ->orderBy(DB::raw('MONTH(publisher.created_at)'));

            if (isset($request['start_date']) && $request['start_date'] != 'null') {
                $query->where('publisher.created_at', '>=', Carbon::create($request['start_date'])->format('Y-m-d'));
                $query->where('publisher.created_at', '<=', Carbon::create($request['end_date'])->format('Y-m-d'));
            }

            if (isset($request['team_in_charge']) && $request['team_in_charge'] > 0) {
                $query->join('users', 'publisher.user_id', 'users.id');
                $query->leftJoin('registration', 'registration.email', 'users.email');
                $query->where('registration.team_in_charge', $request['team_in_charge']);
            }
        } else {
            $query = Publisher::select(DB::raw(
                'IF(registration.team_in_charge IS NOT NULL AND users2.name IS NOT NULL, users2.name, \'Deleted Users\') AS xaxis,
                    COUNT(publisher.id) AS upload,
                    COUNT(IF(publisher.valid = \'valid\', 1, NULL)) AS valid,
                    COUNT(IF(publisher.valid = \'valid\' AND publisher.price_basis IN (\'good\', \'average\'), 1, NULL)) AS quality_price'
            ))
                ->join('users', 'publisher.user_id', 'users.id')
                ->leftJoin('registration', 'users.email', 'registration.email')
                ->join(DB::raw('users users2'), 'registration.team_in_charge', 'users2.id')
                ->groupBy('registration.team_in_charge')
                ->groupBy('users2.name')
                ->orderBy(DB::raw('xaxis'));

            if (isset($request['start_date']) && $request['start_date'] != 'null') {
                $query->where('publisher.created_at', '>=', Carbon::create($request['start_date'])->format('Y-m-d'));
                $query->where('publisher.created_at', '<=', Carbon::create($request['end_date'])->format('Y-m-d'));
            }
        }

        return $query->get();
    }

    public function urlSellerStatisticsQuery($request)
    {
        $date = $request['mode'] === 'Created' ? 'ext_domains.created_at' : 'ext_domains.status_updated_at';

        switch ($request['scope']) {
            case 'monthly':
//                $xaxis = 'CONCAT(MONTHNAME(MAX(ext_domains.created_at)), " ", YEAR(MAX(ext_domains.created_at))) AS xaxis,';
                $xaxis = 'CONCAT(MONTHNAME(MAX(' . $date . ')), " ", YEAR(MAX(' . $date . '))) AS xaxis,';
                break;

            case 'daily':
//                $xaxis = 'CONCAT(MONTH(MAX(ext_domains.created_at)), \'-\', DAY(MAX(ext_domains.created_at)), \'-\', YEAR(MAX(ext_domains.created_at))) AS xaxis,';
                $xaxis = 'CONCAT(MONTH(MAX(' . $date . ')), \'-\', DAY(MAX(' . $date . ')), \'-\', YEAR(MAX(' . $date . '))) AS xaxis,';
                break;

            case 'weekly':
//                $xaxis = 'CONCAT(\'Week \', WEEK(MAX(ext_domains.created_at)), \', \', YEAR(MAX(ext_domains.created_at))) AS xaxis,';
                $xaxis = 'CONCAT(\'Week \', WEEK(MAX(' . $date . ')), \', \', YEAR(MAX(' . $date . '))) AS xaxis,';
                break;

            case 'team':
                $xaxis = 'users.name AS xaxis,';
                break;
        }

        $query = ExtDomain::select(DB::raw($xaxis . '
            COUNT(IF(ext_domains.status = 0, 1, NULL)) AS new,
            COUNT(IF(ext_domains.status = 10, 1, NULL)) AS crawl_failed,
            COUNT(IF(ext_domains.status = 20, 1, NULL)) AS contacts_null,
            COUNT(IF(ext_domains.status = 30, 1, NULL)) AS got_contacts,
            COUNT(IF(ext_domains.status = 50, 1, NULL)) AS contacted,
            COUNT(IF(ext_domains.status = 55, 1, NULL)) AS no_answer,
            COUNT(IF(ext_domains.status = 60, 1, NULL)) AS refused,
            COUNT(IF(ext_domains.status = 70, 1, NULL)) AS in_touch,
            COUNT(IF(ext_domains.status = 80, 1, NULL)) AS undefined,
            COUNT(IF(ext_domains.status = 90, 1, NULL)) AS unqualified,
            COUNT(IF(ext_domains.status = 100, 1, NULL)) AS qualified,
            COUNT(IF(ext_domains.status = 110, 1, NULL)) AS got_email,
            COUNT(IF(ext_domains.status = 120, 1, NULL)) AS contacted_via_form,
            COUNT(ext_domains.status) AS total
        '));

        if ($request['scope'] == 'daily') {
//            $query->groupBy(DB::raw('YEAR(ext_domains.created_at)'));
//            $query->groupBy(DB::raw('MONTH(ext_domains.created_at)'));
//            $query->groupBy(DB::raw('DAY(ext_domains.created_at)'));

//            $query->orderBy(DB::raw('YEAR(ext_domains.created_at)'));
//            $query->orderBy(DB::raw('MONTH(ext_domains.created_at)'));
//            $query->orderBy(DB::raw('DAY(ext_domains.created_at)'));

            $query->groupBy(DB::raw('YEAR(' . $date . ')'));
            $query->groupBy(DB::raw('MONTH(' . $date . ')'));
            $query->groupBy(DB::raw('DAY(' . $date . ')'));

            $query->orderBy(DB::raw('YEAR(' . $date . ')'));
            $query->orderBy(DB::raw('MONTH(' . $date . ')'));
            $query->orderBy(DB::raw('DAY(' . $date . ')'));
        } else if ($request['scope'] == 'weekly') {
//            $query->groupBy(DB::raw('WEEK(ext_domains.created_at)'));
//            $query->groupBy(DB::raw('YEAR(ext_domains.created_at)'));
//
//            $query->orderBy(DB::raw('YEAR(ext_domains.created_at)'));
//            $query->orderBy(DB::raw('WEEK(ext_domains.created_at)'));

            $query->groupBy(DB::raw('WEEK(' . $date . ')'));
            $query->groupBy(DB::raw('YEAR(' . $date . ')'));

            $query->orderBy(DB::raw('YEAR(' . $date . ')'));
            $query->orderBy(DB::raw('WEEK(' . $date . ')'));
        } else if ($request['scope'] == 'monthly') {
//            $query->groupBy(DB::raw('MONTH(ext_domains.created_at)'));
//            $query->groupBy(DB::raw('YEAR(ext_domains.created_at)'));
//
//            $query->orderBy(DB::raw('YEAR(ext_domains.created_at)'));
//            $query->orderBy(DB::raw('MONTH(ext_domains.created_at)'));

            $query->groupBy(DB::raw('MONTH(' . $date . ')'));
            $query->groupBy(DB::raw('YEAR(' . $date . ')'));

            $query->orderBy(DB::raw('YEAR(' . $date . ')'));
            $query->orderBy(DB::raw('MONTH(' . $date . ')'));
        } else if ($request['scope'] == 'team') {
            $query->join('users', 'users.id', 'ext_domains.user_id');
            $query->groupBy('users.id');
            $query->groupBy('users.name');

            $query->orderBy(DB::raw('users.id'));
        }

        if (isset($request['start_date']) && $request['start_date'] != 'null') {
//            $query->where('ext_domains.created_at', '>=', Carbon::create($request['start_date'])->format('Y-m-d'));
//            $query->where('ext_domains.created_at', '<=', Carbon::create($request['end_date'])->format('Y-m-d'));

            $start_date = Carbon::create($request['start_date'])->format('Y-m-d');
            $end_date = Carbon::create($request['end_date'])->format('Y-m-d');

            $query->whereRaw("$date >= '$start_date'");
            $query->whereRaw("$date <= '$end_date'");
        }

        if (isset($request['team_in_charge']) && $request['team_in_charge'] > 0 && $request['scope'] !== 'team') {
            $query->where('ext_domains.user_id', $request['team_in_charge']);
        }

        if ($request['mode'] === 'Status') {
            $query->whereNotNull('ext_domains.status_updated_at');
        }

        return $query->get();
    }

    public function prospectQualifiedVsRegisteredQuery($request)
    {
        switch ($request['scope']) {
            case 'monthly':
                $xaxis = 'CONCAT(MONTHNAME(MAX(ext_domains.created_at)), " ", YEAR(MAX(ext_domains.created_at))) AS xaxis,
                (
                    SELECT COUNT(registration.id)
                    FROM registration
                    WHERE registration.deleted_at IS NULL
                    AND MONTH(registration.created_at) = MONTH(MAX(ext_domains.created_at))
                    AND YEAR(registration.created_at) = YEAR(MAX(ext_domains.created_at))
                ) AS total,';
                break;

            case 'team':
                $xaxis = 'users.name AS xaxis,
                (
                    SELECT COUNT(registration.created_at)
                    FROM registration
                    WHERE registration.deleted_at IS NULL
                    AND registration.team_in_charge = ext_domains.user_id
                ) AS total,';
                break;
        }

        $query = ExtDomain::select(DB::raw(
            $xaxis .
            'COUNT(IF(ext_domains.status = 100, 1, NULL)) AS qualified'
        ));

        if ($request['scope'] == 'monthly') {
            $query->groupBy(DB::raw('MONTH(ext_domains.created_at)'));
            $query->groupBy(DB::raw('YEAR(ext_domains.created_at)'));

            $query->orderBy(DB::raw('YEAR(ext_domains.created_at)'));
            $query->orderBy(DB::raw('MONTH(ext_domains.created_at)'));
        } else {
            $query->join('users', 'users.id', 'ext_domains.user_id');
            $query->groupBy('ext_domains.user_id');
            $query->groupBy('users.name');

            $query->orderBy(DB::raw('users.id'));
        }

        if (isset($request['start_date']) && $request['start_date'] != 'null') {
            $query->where('ext_domains.created_at', '>=', Carbon::create($request['start_date'])->format('Y-m-d'));
            $query->where('ext_domains.created_at', '<=', Carbon::create($request['end_date'])->format('Y-m-d'));
        }

        return $query->get();
    }
}
