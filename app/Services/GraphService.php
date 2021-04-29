<?php

namespace App\Services;

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
}
