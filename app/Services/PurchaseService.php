<?php

namespace App\Services;

use App\Models\PaymentType;
use App\Models\Purchases;
use App\Models\PurchaseType;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PurchaseService
{
    public function index($request) {
        $purchases = Purchases::select('*');

        switch ($request->mode) {
            case 'tools':
                $purchases = $purchases->whereIn('purchaseable_type', [Tool::class]);
                break;
            case 'manual':
                $purchases = $purchases->whereNull('purchaseable_type')
                    ->whereNull('purchaseable_id');
                break;
            default:

        }

        $purchases = $purchases->with(
            'type.category',
            'paymentType:id,type',
            'user:id,username,name',
            'files:id,path,fileable_id,is_invoice,is_receipt'
        );

        $purchases = $this->generateFilterQuery($request, $purchases, 'page');

        $purchases = $purchases->orderBy('purchased_at', 'desc');

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
                $buyers = $buyers->whereIn('purchaseable_type', [Tool::class]);
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

    public function store ($request, $mod, $model = null) {
        DB::transaction(function () use ($request, $mod, $model) {
            // save file
            $receipt_name = '';
            $invoice_name = '';
            $inputs = $mod === 'manual' ? $request->except('invoice', 'receipt', 'mode') : $request->all();

            // process receipt
            if ($request->hasFile('receipt') || ($request->purchase_receipt != null || $request->purchase_receipt != '')) {
                $receipt = $mod === 'manual' ? $request->receipt : $request->purchase_receipt;

                $receipt_name = time() . '-purchases-receipt.' . $receipt->getClientOriginalExtension();
                move_file_to_storage($receipt, 'images/purchases', $receipt_name);
            }

            // process invoice
            if ($request->hasFile('invoice') || ($request->purchase_invoice != null || $request->purchase_invoice != '')) {
                $invoice = $mod === 'manual' ? $request->invoice : $request->purchase_invoice;

                $invoice_name = time() . '-purchases-invoice.' . $invoice->getClientOriginalExtension();
                move_file_to_storage($invoice, 'images/purchases', $invoice_name);
            }

            if ($mod !== 'manual') {
                $purchase = $model->purchases()->create([
                    'type_id' => $inputs['purchase_type_id'],
                    'amount' => $inputs['purchase_amount'],
                    'from' => $inputs['purchase_from'],
                    'payment_type_id' => $inputs['purchase_payment_type_id'],
                    'notes' => $inputs['purchase_notes'],
                    'purchased_at' => $inputs['purchase_purchased_at'],
                    'user_id' => auth()->id(),
                    'is_manual' => 0
                ]);

                $purchase->files()->create([
                    'path' => '/images/purchases/' . $receipt_name,
                    'is_receipt' => 1
                ]);

                $purchase->files()->create([
                    'path' => '/images/purchases/' . $invoice_name,
                    'is_invoice' => 1
                ]);
            } else {
                $purchase = Purchases::create($inputs + [
                        'user_id' => auth()->id(),
                        'is_manual' => 1
                    ]);

                // save receipt
                $purchase->files()->create([
                    'path' => '/images/purchases/' . $receipt_name,
                    'is_receipt' => 1
                ]);

                // save invoice
                $purchase->files()->create([
                    'path' => '/images/purchases/' . $invoice_name,
                    'is_invoice' => 1
                ]);
            }
        });
    }

    public function update ($request) {
        $inputs = $request->except('file', '_method', 'mode', 'receipt', 'invoice');

        $purchase = Purchases::find($request->id);

        // process receipt
        if ($request->hasFile('receipt')) {
            $path = $purchase->receipt ? public_path() . $purchase->receipt->path : null;

            if ($path != null && File::exists($path)) {
                unlink($path);
            }

            $receipt_name = time() . '-purchases-receipt.' . $request->receipt->getClientOriginalExtension();
            move_file_to_storage($request->receipt, 'images/purchases', $receipt_name);

            if ($path != null) {
                $purchase->receipt()->update([
                    'path' => '/images/purchases/' . $receipt_name,
                    'is_receipt' => 1
                ]);
            } else {
                $purchase->receipt()->create([
                    'path' => '/images/purchases/' . $receipt_name,
                    'is_receipt' => 1
                ]);
            }
        }

        // process invoice
        if ($request->hasFile('invoice')) {
            $path = $purchase->invoice ? public_path() . $purchase->invoice->path : null;

            if ($path != null && File::exists($path)) {
                unlink($path);
            }

            $invoice_name = time() . '-purchases-invoice.' . $request->invoice->getClientOriginalExtension();
            move_file_to_storage($request->invoice, 'images/purchases', $invoice_name);

            if ($path != null) {
                $purchase->invoice()->update([
                    'path' => '/images/purchases/' . $invoice_name,
                    'is_invoice' => 1
                ]);
            } else {
                $purchase->invoice()->create([
                    'path' => '/images/purchases/' . $invoice_name,
                    'is_invoice' => 1
                ]);
            }
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

    public function getModules () {
        $modules = Purchases::select('purchases.purchaseable_type')
            ->groupBy('purchases.purchaseable_type')
            ->orderBy('purchases.purchaseable_type', 'asc')
            ->get();

        foreach ($modules as $module) {
            if ($module->purchaseable_type) {
                $module['name'] = class_basename($module->purchaseable_type);
                $module['value'] = strtolower(class_basename($module->purchaseable_type));
            } else {
                $module['name'] = 'Manual';
                $module['value'] = 'manual';
            }
        }

        return $modules;
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

        if (isset($request->module) && $request->module != null) {
            switch ($request->module) {
                case 'tool':
                    $query = $query->whereIn('purchaseable_type', [Tool::class]);
                    break;
                case 'manual':
                    $query = $query->whereNull('purchaseable_type')
                        ->whereNull('purchaseable_id');
                    break;
                default:
            }
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
