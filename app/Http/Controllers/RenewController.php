<?php

namespace App\Http\Controllers;

use App\Jobs\AddPurchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RenewController extends Controller
{
    public function renew (Request $request)
    {
        $request->validate([
            'id' => 'required',
            'expired_at' => 'required',
            'registered_at' => 'required',
            'renewal_period' => 'required',
        ]);

        DB::transaction(function () use ($request) {

            // get model namespace
            $modelNameSpace = $request->model;

            // update model expiration date
            $model = $modelNameSpace::find($request->id);
            $model->expired_at = Carbon::parse($request->expired_at)->addMonths($request->renewal_period)->format('Y-m-d');
            $model->save();

            // save purchase details if is purchased
            if ($request->is_purchased === true || $request->is_purchased === 'true') {
                AddPurchase::dispatchNow($request, $model, 'renew');
            }

        });
    }
}
