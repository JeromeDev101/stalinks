<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests\WalletTransaction\AddWalletRequest;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\TotalWallet;
use Illuminate\Support\Facades\Auth;
use App\Models\Publisher;

class WalletTransactionController extends Controller
{
    public function getList(Request $request) {
        $filter = $request->all();
        $user = Auth::user();
        $deposit = 0;

        $list = WalletTransaction::select('wallet_transactions.*')
                        ->with('user:id,name')
                        ->with('payment_type:id,type')
                        ->orderBy('id', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 ){
            $list->where('user_id', $user->id);
        }

        if( isset($filter['buyer']) && !empty($filter['buyer']) ){
            $list->where('wallet_transactions.user_id', $filter['buyer']);
        }

        if( isset($filter['payment_type']) && !empty($filter['payment_type']) ){
            $list->where('wallet_transactions.payment_via_id', $filter['payment_type']);
        }

//        if( isset($filter['date']) && !empty($filter['date']) ){
//            $list->where('wallet_transactions.date', $filter['date']);
//        }

        if (isset($filter['date'])) {
            $filter['date'] = json_decode($filter['date']);
        }

        if( isset($filter['date']) && !empty($filter['date']) && $filter['date']->startDate != ''){
            $list->where('wallet_transactions.date', '>=', Carbon::create($filter['date']->startDate)
                ->format('Y-m-d'));
            $list->where('wallet_transactions.date', '<=', Carbon::create($filter['date']->endDate)
                ->format('Y-m-d'));
        }

        $list = $list->get();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
                    ->where('user_id', $user->id)
                    ->get();

        if( isset($wallet_transaction[0]['amount_usd']) ){
            $deposit = number_format($wallet_transaction[0]['amount_usd'],2);
        }

        return [
            'data' => $list,
            'deposit' => $deposit
        ];
    }

    public function getListBuyer() {
        $columns = [
            'users.id',
            'users.name',
            'users.username',
            'users.email',
            'users.role_id',
            'users.credit_auth',
        ];

        $list = User::select($columns)
                    ->where('users.status', 'active')
                    ->where('role_id', 5)
                    ->orderBy('users.username', 'asc');

        return [
            'data' => $list->get()
        ];
    }

    public function getListBuyerWithWalletTransaction()
    {
        $list = WalletTransaction::select('users.id', 'users.name', 'users.email')
        ->join('users', 'wallet_transactions.user_id', 'users.id')
        ->groupBy('wallet_transactions.user_id', 'users.id', 'users.name', 'users.email')
        ->get();

        return response()->json([
            'data' => $list
        ]);
    }

    public function getListSellerTeam() {
        $list = User::select('id', 'username', 'status')->whereIn('role_id', [6, 1])->where('isOurs', 0)->orderBy('username', 'asc');
        return [
            'data' => $list->get()
        ];
    }

    public function getListSeller() {
        $list = Publisher::select('user_id')->distinct()->get()->toArray();
        $ids = [];
        foreach( $list as $id ){
            $ids[] = $id['user_id'];
        }
        // $list = User::whereIn('id', $ids)->orderBy('username', 'asc');
        $list = User::select('users.*', 'registration.team_in_charge')
            ->where('role_id', 6)
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->orderBy('username', 'asc');
        return [
            'data' => $list->get()
        ];
    }

    public function addWallet(AddWalletRequest $request) {
        $image = $request->file;
        $paymentType = $request->get('payment_type');

        $fileName = $paymentType != 1 ? $this->moveFileToStorage($image) : '';

        // If payment type is paypal dont insert proof_doc field
        $data = $paymentType == 1 ? [
            'user_id' => $request->user_id_buyer,
            'payment_via_id' => $request->payment_type,
            'amount_usd' => $request->amount_usd,
            'date' => date('Y-m-d'),
            'proof_doc' => '/',
            'admin_confirmation' => 'Paid',
        ] : [
            'user_id' => $request->user_id_buyer,
            'payment_via_id' => $request->payment_type,
            'amount_usd' => $request->amount_usd,
            'date' => date('Y-m-d'),
            'proof_doc' => '/images/wallet_transaction/'.$fileName,
            'admin_confirmation' => 'Not Paid',
        ];

        WalletTransaction::create($data);

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

    /**
     * Move file to wallet transaction folder and return file name
     * @param $file
     * @return string
     */
    private function moveFileToStorage($file)
    {
        $new_name = date('Ymd').time() . '-transaction.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/wallet_transaction'), $new_name);

        return $new_name;
    }
}
