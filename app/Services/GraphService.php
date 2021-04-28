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
}
