<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;

class BuyerBillingController extends Controller
{
    public function getList(Request $request) {
        $list = Backlink::select('backlinks.*')
                    ->where('status', 'Live')
                    ->orderBy('id', 'desc')
                    ->get();

        return [
            'data' => $list
        ];
    }
}
