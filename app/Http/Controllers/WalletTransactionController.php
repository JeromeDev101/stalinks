<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\TotalWallet;
use Illuminate\Support\Facades\Auth;

class WalletTransactionController extends Controller
{
    public function getList(Request $request) {
        $user = Auth::user();

        $list = WalletTransaction::with('user:id,name')
                        ->with('payment_type:id,type')
                        ->orderBy('id', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 ){
            $list->where('user_id', $user->id);
        }

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
            'users.credit_auth',
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

        $request->validate([
            'payment_type' => 'required',
            'amount_usd' => 'required',
            'user_id_buyer' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file;
        $new_name = date('Ymd').time() . '-transaction.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/wallet_transaction'), $new_name);

        WalletTransaction::create([
            'user_id' => $request->user_id_buyer,
            'payment_via_id' => $request->payment_type,
            'amount_usd' => $request->amount_usd,
            'date' => date('Y-m-d'),
            'proof_doc' => '/images/wallet_transaction/'.$new_name,
            'admin_confirmation' => 'Paid',
        ]);

        $total_wallet = TotalWallet::where('user_id', $request->user_id_buyer)->first();

        if( $total_wallet['user_id'] == ""){
            TotalWallet::create([
                'user_id' => $request->user_id_buyer,
                'total_wallet' => $request->amount_usd,
            ]);
        }else{
            $amount = floatVal($total_wallet['total_wallet']) + floatVal($request->amount_usd);
            $total_wallet->update(['total_wallet' => $amount]);
        }

        return response()->json(['success' => true], 200);
    }

    public function updateWallet(Request $request) {
        $wallet_transaction = WalletTransaction::find($request->id);
        $proof_doc = $wallet_transaction->proof_doc;

        $request->validate([
            'payment_type' => 'required',
            'amount_usd' => 'required',
            'user_id_buyer' => 'required',
            'admin_confirmation' => 'required',
        ]);

        if( $request->file != "undefined"){
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $image = $request->file;
            $new_name = date('Ymd').time() . '-transaction.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/wallet_transaction'), $new_name);

            $proof_doc = '/images/wallet_transaction/'.$new_name;

            $file_name = str_replace('/images/wallet_transaction/','',$wallet_transaction->proof_doc);
            $path = public_path()."/images/wallet_transaction/".$file_name;

            if( file_exists($path) ){
                unlink($path);
            }
        }

        $wallet_transaction->update([
            'user_id' => $request->user_id_buyer,
            'payment_via_id' => $request->payment_type,
            'amount_usd' => $request->amount_usd,
            'date' => date('Y-m-d'),
            'proof_doc' => $proof_doc,
            'admin_confirmation' => $request->admin_confirmation
        ]);

        return response()->json(['success'=>true],200);

    }
}
