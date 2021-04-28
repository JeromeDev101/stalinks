<?php

namespace App\Http\Controllers;

use App\Services\GraphService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphsController extends Controller
{
    protected $graphService;

    public function __construct(GraphService $graphService)
    {
        $this->graphService = $graphService;
    }

    public function getOrdersGraph(Request $request)
    {
        $data = DB::select('call orders_graph(?, ?, ?)', [
            $request->has('team_in_charge') && $request->get('team_in_charge') != null ? $request->get('team_in_charge') : 0,
            $request->has('start_date') && $request->get('start_date') != 'null' ? Carbon::parse($request->get('start_date'))->format('Y-m-d') : '2000-01-01',
            $request->has('end_date') &&  $request->get('end_date') != 'null' ? Carbon::parse($request->get('end_date'))->format('Y-m-d') : '2100-12-31',
        ]);

        return response()->json($data);
    }

    public function getSellerValidGraph(Request $request)
    {
        $data = DB::select('call seller_valid_graph(?, ?, ?, ?)', [
            $request->has('start_date') && $request->get('start_date') != 'null' ? Carbon::parse($request->get('start_date'))->format('Y-m-d') : '2000-01-01',
            $request->has('end_date') &&  $request->get('end_date') != 'null' ? Carbon::parse($request->get('end_date'))->format('Y-m-d') : '2100-12-31',
            $request->get('scope'),
            $request->get('team_in_charge')
        ]);

        return response()->json($data);
    }

    public function getUrlValidGraph(Request $request)
    {
        return response()->json($this->graphService->urlValidationQuery($request->all()));
    }
}
