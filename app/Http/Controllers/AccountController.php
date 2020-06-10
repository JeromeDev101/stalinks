<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    public function store(AccountRequest $request)
    {
        $input = $request->all();
        unset($input['c_password']);
        $input['password'] = Hash::make($input['password']);
        $registration = Registration::create($input);

        return response()->json(['success' => true, 'data' => $registration], 200);
    }

    public function getList(Request $request)
    {
        $status = $request->status;
        $type = $request->type;
        $search = $request->search;

        $list = Registration::when( $status, function($query) use ($status){
            return $query->where( 'status', $status );
        })->when( $type, function($query) use ($type){
            return $query->where( 'type', $type );
        })->when( $search, function($query) use ($search){
            return $query->where( 'name', 'LIKE', '%'.$search.'%' )
                ->orWhere( 'email', 'LIKE', '%'.$search.'%' );
        })->get();
        

        return response()->json([
            'data' => $list
        ],200);
    }

    public function edit(UpdateAccountRequest $request)
    {
        $response['success'] = false;
        $input = $request->all();
        unset($input['c_password']);

        if (isset($input['password']) && $input['password'] != '') {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $account = Registration::findOrFail($input['id']);
        if (!$account) {
            return response()->json($response);
        }

        $account->update($input);
        $response['success'] = true;
        return response()->json($response);
    }
}
