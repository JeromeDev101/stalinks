<?php

namespace App\Http\Controllers;

use App\Models\UsersPaymentType;
use Illuminate\Http\Request;

class UsersPaymentTypeController extends Controller
{
    public function getHistory ($id) {
        $solutions = UsersPaymentType::withTrashed()
            ->where('user_id', $id)
            ->with('user', 'paymentType')
            ->orderByRaw('-deleted_at ASC')
            ->orderBy('id', 'DESC')
            ->get();

        return response()->json($solutions);
    }
}
