<?php

namespace App\Http\Controllers;

use App\Events\AddWalletEvent;
use App\Events\RefundRequestProcessedEvent;
use App\Services\InvoiceService;
use Carbon\Carbon;
use App\Http\Requests\WalletTransaction\AddWalletRequest;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\User;
use App\Models\TotalWallet;
use Illuminate\Support\Facades\Auth;
use App\Models\Publisher;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Psr7\uri_for;

class WalletTransactionController extends Controller
{
    public function getList(Request $request) {
        $filter = $request->all();
        $user = Auth::user();
        $deposit = 0;

        $list = WalletTransaction::select('wallet_transactions.*')
                        ->with('user:id,name,username')
                        ->with('payment_type:id,type')
                        ->orderBy('id', 'desc');

        if( !$user->isAdmin() && $user->role->id != 7 && $user->role->id !== 8){
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
            $list->whereDate('wallet_transactions.date', '>=', Carbon::create($filter['date']->startDate)
                ->format('Y-m-d'));
            $list->whereDate('wallet_transactions.date', '<=', Carbon::create($filter['date']->endDate)
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
        $list = WalletTransaction::select('users.id', 'users.name', 'users.email', 'users.username')
        ->join('users', 'wallet_transactions.user_id', 'users.id')
        ->orderBy('users.username')
        ->groupBy('wallet_transactions.user_id', 'users.id', 'users.name', 'users.email')
        ->get();

        return response()->json([
            'data' => $list
        ]);
    }

    public function getListSellerTeam() {
        $list = User::select('id', 'username', 'status')->whereIn('role_id', [15, 1])->where('isOurs', 0)->orderBy('username', 'asc');
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
            ->where('users.status', 'active')
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->orderBy('username', 'asc');
        return [
            'data' => $list->get()
        ];
    }

    public function refundWallet(Request $request) {
        $user_id = Auth::user()->id;
        $result = false;

        $wallet_transaction = WalletTransaction::where('user_id', $user_id)
                    ->where('admin_confirmation', 'Refund processing')
                    ->count();

        if($wallet_transaction > 0) {
            $result = true;
        } else {
            $data = [
                'user_id' => $user_id,
                'payment_via_id' => $request->payment_id,
                'amount_usd' => $request->amount,
                'date' => date('Y-m-d'),
                'proof_doc' => '/',
                'admin_confirmation' => 'Refund processing',
            ];

            $wallet = WalletTransaction::create($data);

            event(new RefundRequestProcessedEvent($wallet, 'request'));
        }

        return response()->json(['success' => $result], 200);
    }

    public function checkOnProcessRefund() {
        $user_id = Auth::user()->id;
        $result = false;

        $wallet_transaction = WalletTransaction::where('user_id', $user_id)
                    ->where('admin_confirmation', 'Refund processing')
                    ->count();

        if($wallet_transaction > 0) {
            $result = true;
        }

        return response()->json(['result'=>$result], 200);
    }

    public function addWallet(AddWalletRequest $request, InvoiceService $invoice) {
        if (Gate::denies('create-billing-wallet-transaction')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

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

        $wallet = WalletTransaction::create($data);

        if ($paymentType == 1) {
            $payload = \GuzzleHttp\json_decode($request->get('payload'))->data->result;
            $payload->invoice_id = $wallet->id;
            $wallet->update([
                'invoice' => '/storage/app/STAL-' . $wallet->id . '.pdf'
            ]);

            $invoice->generateCreditInvoice($payload);
        }

        // add subscription code then send email
        $this->addUserSubscriptionCode($wallet->user, $wallet->amount_usd);

        return response()->json(['success' => true], 200);
    }

    protected function addUserSubscriptionCode($user, $amount) {
        if ($user->subscription_code === null) {
            $sub = User::find($user->id);

            if ($sub) {
                $sub->subscription_code = md5(uniqid(rand(), true));
                $sub->save();

                if ($sub->save()) {
                    event(new AddWalletEvent($sub, $amount));
                }
            }
        } else {
            event(new AddWalletEvent($user, $amount));
        }
    }

    public function updateWallet(Request $request) {
        if (Gate::denies('update-billing-wallet-transaction')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

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

            if( file_exists($path) && $wallet_transaction->admin_confirmation != 'Refunded' && $wallet_transaction->admin_confirmation != 'Refund processing'){
                unlink($path);
            }
        }

        if ($request->admin_confirmation != $wallet_transaction->admin_confirmation) {
            if ($request->admin_confirmation === 'Refunded') {
                event(new RefundRequestProcessedEvent($wallet_transaction, 'refund'));
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

    public function downloadPaypalInvoice($id)
    {
        $file = Storage::get('STAL-' . $id . '.pdf');

        return $file;
    }
}
