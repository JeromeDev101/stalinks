<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;

class WalletTransactionController extends Controller
{
    public function getList(Request $request) {
        $list = WalletTransaction::with('user:id,name');

        return [
            'data' => $list->get()
        ];
    }
}
