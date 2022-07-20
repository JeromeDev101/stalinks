<?php

namespace App\Services;

use App\Models\PaymentType;
use App\Models\Purchases;
use App\Models\PurchaseType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PurchaseService
{
    public function index($request) {
        $purchases = Purchases::select('*');

        switch ($request->mode) {
            case 'tools':

                break;
            case 'manual':
                $purchases = $purchases->whereNull('purchaseable_type')
                    ->whereNull('purchaseable_id');
                break;
            default:

        }

        $purchases = $purchases->with(
            'user:id,username,name',
            'file:id,path,fileable_id',
            'type.category',
            'paymentType:id,type'
        );

        $purchases = $this->generateFilterQuery($request, $purchases, 'page');

        if( isset($request->paginate) && $request->paginate == 'All' ){
            $purchases = $purchases->get();

            return [
                'data' => $purchases,
                'total' => $purchases->count(),
            ];
        }else{
            $purchases = $purchases->paginate($request->paginate);

            return $purchases;
        }
    }

    public function selection ($mod) {
        $buyers = Purchases::select('purchases.user_id as id', 'users.username', 'users.name')
            ->leftJoin('users', 'users.id', '=', 'purchases.user_id');

        switch ($mod) {
            case 'tools':

                break;
            case 'manual':
                $buyers = $buyers->whereNull('purchaseable_type')
                    ->whereNull('purchaseable_id');
                break;
            default:

        }

        $buyers = $buyers->groupBy('purchases.user_id', 'users.username')
            ->orderBy('users.username', 'asc')
            ->get();

        return $buyers;
    }

    public function store ($request, $mod) {
        // save file
        $filename = '';
        $inputs = $request->except('file', 'mode');

        if ($request->hasFile('file')) {
            $filename = time() . '-purchases.' . $request->file->getClientOriginalExtension();
            move_file_to_storage($request->file, 'images/purchases', $filename);
        }

        if ($mod !== 'manual') {

        } else {
            $purchase = Purchases::create($inputs + [
                'user_id' => auth()->id(),
                'is_manual' => 1
            ]);

            $purchase->file()->create([
                'path' => '/images/purchases/' . $filename
            ]);
        }
    }

    public function update ($request) {
        $inputs = $request->except('file', '_method', 'mode');

        $purchase = Purchases::find($request->id);

        // process file
        if ($request->hasFile('file')) {

            $path = public_path() . $purchase->file->path;

            if (File::exists($path)) {
                unlink($path);
            }

            $filename = time() . '-purchases.' . $request->file->getClientOriginalExtension();
            move_file_to_storage($request->file, 'images/purchases', $filename);

            $purchase->file()->update([
                'path' => '/images/purchases/' . $filename
            ]);
        }

        if (!$purchase) {
            return response()->json(['success' => false], '422');
        }else{
            $purchase->update($inputs);
            return response()->json(['success' => true], '200');
        }
    }

    public function getCategoryReportData ($request) {
        $query = Purchases::select(DB::raw('
            CONCAT(MONTHNAME(MAX(purchased_at)), " ", YEAR(MAX(purchased_at))) as xaxis,
            SUM(CASE WHEN purchase_types.purchase_category_id = 1 THEN purchases.amount ELSE 0 END) as opCostIt,
            SUM(CASE WHEN purchase_types.purchase_category_id = 2 THEN purchases.amount ELSE 0 END) as marketingContent,
            SUM(CASE WHEN purchase_types.purchase_category_id = 3 THEN purchases.amount ELSE 0 END) as marketingSEO,
            SUM(CASE WHEN purchase_types.purchase_category_id = 4 THEN purchases.amount ELSE 0 END) as marketingTravel,
            SUM(CASE WHEN purchase_types.purchase_category_id = 5 THEN purchases.amount ELSE 0 END) as itTool
        '))
        ->join('purchase_types', 'purchases.type_id', 'purchase_types.id')
        ->leftJoin('purchase_categories', 'purchase_types.purchase_category_id', 'purchase_categories.id')
        ->groupBy(DB::raw('MONTH(purchased_at)'))
        ->groupBy(DB::raw('YEAR(purchased_at)'))
        ->orderBy(DB::raw('YEAR(purchased_at)'))
        ->orderBy(DB::raw('MONTH(purchased_at)'));

        $query = $this->generateFilterQuery($request, $query, 'graph');

        return $query->get();
    }

    public function getPurchaseTypeReportData ($request) {
        $types = PurchaseType::select('id', 'name')->get()->toArray();

        $queryString = implode(',', $this->generateQueryString($types, 'purchases.type_id', 'name'));

        $query = Purchases::select(DB::raw('CONCAT(MONTHNAME(MAX(purchased_at)), " ", YEAR(MAX(purchased_at))) as xaxis,' . $queryString))
            ->join('purchase_types', 'purchases.type_id', 'purchase_types.id')
            ->groupBy(DB::raw('MONTH(purchased_at)'))
            ->groupBy(DB::raw('YEAR(purchased_at)'))
            ->orderBy(DB::raw('YEAR(purchased_at)'))
            ->orderBy(DB::raw('MONTH(purchased_at)'));

        $query = $this->generateFilterQuery($request, $query, 'graph');

        return $query->get();
    }

    public function getPaymentMethodReportData ($request) {
        $paymentMethods = PaymentType::select('id', 'type')->get()->toArray();

        $queryString = implode(',', $this->generateQueryString($paymentMethods, 'purchases.payment_type_id', 'type'));

        $query = Purchases::select(DB::raw('CONCAT(MONTHNAME(MAX(purchased_at)), " ", YEAR(MAX(purchased_at))) as xaxis,' . $queryString))
            ->join('payment_type', 'purchases.payment_type_id', 'payment_type.id')
            ->groupBy(DB::raw('MONTH(purchased_at)'))
            ->groupBy(DB::raw('YEAR(purchased_at)'))
            ->orderBy(DB::raw('YEAR(purchased_at)'))
            ->orderBy(DB::raw('MONTH(purchased_at)'));

        $query = $this->generateFilterQuery($request, $query, 'graph');

        return $query->get();
    }

    protected function generateFilterQuery ($request, $query, $mod) {
        if (isset($request->category_id) && $request->category_id != null) {
            $query = $query->whereHas('type.category', function($q) use ($request) {
                $q->where('id', $request->category_id);
            });
        }

        if (isset($request->type_id) && $request->type_id != null) {
            $query = $query->where('type_id', $request->type_id);
        }

        if (isset($request->from) && $request->from != null) {
            $query = $query->where('from', 'like', '%' . $request->from . '%');
        }

        if (isset($request->user_id) && $request->user_id != null) {
            $query = $query->where('user_id', $request->user_id);
        }

        if (isset($request->payment_type_id) && $request->payment_type_id != null) {
            $query = $query->where('payment_type_id', $request->payment_type_id);
        }

        if (isset($request['date'])) {
            $request['date'] = json_decode($request['date']);
        }

        if( isset($request->date) && !empty($request->date) && $request->date->startDate != ''){
            $query = $query->whereDate('purchases.purchased_at', '>=', Carbon::create($request['date']->startDate)
                ->format('Y-m-d'));
            $query = $query->whereDate('purchases.purchased_at', '<=', Carbon::create($request['date']->endDate)
                ->format('Y-m-d'));
        } else {
            if ($mod === 'graph') {
                $query = $query->whereBetween('purchases.purchased_at', [
                    Carbon::now()->startOfMonth()->subMonths(5),
                    Carbon::now()->endOfMonth()
                ]);
            }
        }

        return $query;
    }

    protected function generateQueryString ($array, $column, $mod) {
        $strings = [];

        foreach ($array as $value) {
            $strings[] = "SUM(CASE WHEN " . $column
                . " = " . $value['id']
                . " THEN purchases.amount ELSE 0 END) as "
                . strtolower(str_replace(' ', '', $value[$mod]));
        }

        return $strings;
    }
}
