<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class SellerBillingController extends Controller
{
    public function getList(Request $request) {
        $columns = [
            'backlinks.*',
            'billing.proof_doc_path',
            'billing.admin_confirmation',
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('billing', 'backlinks.id', '=', 'billing.id_backlink')
                    ->with(['publisher' => function($q){
                        $q->with('user:id,name');
                    }])
                    ->with('user:id,name')
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
