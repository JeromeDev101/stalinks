<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class SellerBillingController extends Controller
{
    public function getList(Request $request) {
        $list = Backlink::with(['publisher' => function($query){
            $query->with('user:id,name');
        }, 'user'])
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

        return [
            'data' => $list->get(),
        ];
    }

    public function payBilling(Request $request) {
        dd($request->all());
    }
}
