<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;

class WalletTransactionController extends Controller
{
    public function getList(Request $request) {
        $list = WalletTransaction::with('user:id,name');

        return [
            'data' => $list->get()
        ];
    }

    public function getListBuyer() {
        $columns = [
            'users.id',
            'users.name',
            'users.email',
            'users.role_id',
            'registration.name as reg_name',
        ];

        $list_registration = User::select($columns)
                    ->leftJoin('registration', function($join){
                        $join->on('users.email' , '=', 'registration.email')
                            ->where('registration.type', 'Buyer');
                    })
                    ->whereNotNull('registration.name');

        $list = User::select($columns)
                    ->leftJoin('registration', function($join){
                        $join->on('users.email' , '=', 'registration.email')
                            ->where('registration.type', 'Buyer');
                    })
                    ->where('role_id', 5)
                    ->union($list_registration);

        return [
            'data' => $list->get()
        ];
    }

    public function addWallet(Request $request) {
        dd($request->all());
    }
}
