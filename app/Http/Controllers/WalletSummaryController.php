<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registration;
use App\Models\Backlink;
use Illuminate\Support\Facades\DB;

class WalletSummaryController extends Controller
{
    public function getList(Request $request) {
        $columns = [
            'users.id',
            'users.name',
            'users.username',
            'users.status',
            DB::raw('
            SUM(CASE WHEN wallet_transactions.admin_confirmation = "Paid"
            THEN wallet_transactions.amount_usd ELSE NULL END) as deposit
            '),
        ];

        $checkAdmin = Auth::user()->type != 10 && Auth::user()->role_id != 8;
        $user_id = Auth::user()->id;

        $user_buyers = User::select($columns)
                    ->where('role_id', 5)
                    ->leftjoin('registration', 'users.email', '=', 'registration.email')
                    ->leftjoin('wallet_transactions', 'users.id', '=', 'wallet_transactions.user_id')
                    ->where('users.isOurs', 1)
                    // ->where('users.status', 'active')
                    ->where('registration.is_sub_account', 0)
                    ->when(isset($request->buyer), function($query) use ($request) {
                        if (is_array($request->buyer)) {
                            return $query->whereIn('users.id', $request->buyer);
                        } else {
                            return $query->where('users.id', $request->buyer);
                        }
                    })
                    ->when(isset($request->year), function($query) use ($request) {
                        return $query->whereYear('wallet_transactions.date', '=', $request->year);
                    })
                    ->when(isset($request->month), function($query) use ($request) {
                        return $query->whereMonth('wallet_transactions.date', '=', $request->month);
                    })
                    ->when(isset($request->date), function($query) use ($request) {
                        $request['date'] = json_decode($request->date);

                        if( isset($request['date']) && !empty($request['date']) && $request['date']->startDate != ''){
                            return $query->whereDate('wallet_transactions.date', '>=', Carbon::create($request['date']->startDate)->format('Y-m-d'))
                                ->whereDate('wallet_transactions.date', '<=', Carbon::create($request['date']->endDate)->format('Y-m-d'));
                        }
                    })
                    ->when($checkAdmin, function($query) use ($user_id) {
                        return $query->where('users.id', $user_id);
                    })
                    ->having('deposit', '>', 0)
                    ->groupBy('users.id', 'users.name', 'users.username')
                    ->get();

        foreach($user_buyers as $key => $user_buyer) {
            $user_buyers[$key]['order_refund'] = $this->getTotalPurchase($user_buyer->id, 'order_refund', $request->year, $request->month);
            $user_buyers[$key]['orders'] = $this->getTotalPurchase($user_buyer->id, 'orders', $request->year, $request->month);
            $user_buyers[$key]['order_live'] = $this->getTotalPurchase($user_buyer->id, 'order_live', $request->year, $request->month);
            $user_buyers[$key]['order_cancel'] = $this->getTotalPurchase($user_buyer->id, 'order_cancel', $request->year, $request->month);
            $user_buyers[$key]['wallet'] = $user_buyer->deposit == null ? 0:$user_buyer->deposit - $this->getTotalPurchase($user_buyer->id, 'order_live', $request->year, $request->month) + $this->getTotalPurchase($user_buyer->id, 'order_refund', $request->year, $request->month);
            $user_buyers[$key]['credit_left'] = $user_buyer->deposit == null ? 0:$user_buyer->deposit - $this->getTotalPurchase($user_buyer->id, 'valid_orders', $request->year, $request->month) - $this->getTotalRefunded($user_buyer->id) + $this->getTotalPurchase($user_buyer->id, 'order_refund', $request->year, $request->month);
            $user_buyers[$key]['valid_orders'] = $this->getTotalPurchase($user_buyer->id, 'valid_orders', $request->year, $request->month);
            $user_buyers[$key]['refund_request'] = $this->getTotalRefunded($user_buyer->id);

        }

        $user_buyers = $user_buyers->toArray();

        $user_buyers['data'] = $user_buyers;
        $user_buyers['total_deposit'] = array_sum(array_column($user_buyers, 'deposit'));;
        $user_buyers['total_orders'] = array_sum(array_column($user_buyers, 'orders'));;
        $user_buyers['total_orders_cancelled'] = array_sum(array_column($user_buyers, 'order_cancel'));;
        $user_buyers['total_valid_orders'] = array_sum(array_column($user_buyers, 'valid_orders'));;
        $user_buyers['total_purchase'] = array_sum(array_column($user_buyers, 'order_live'));;
        $user_buyers['total_refund_orders'] = array_sum(array_column($user_buyers, 'order_refund'));;
        $user_buyers['total_wallet'] = array_sum(array_column($user_buyers, 'wallet'));;

        return $user_buyers;
    }

    // private function getTotal($user_id, $type) {

    //     $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
    //     $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

    //     $total_paid = Backlink::selectRaw('SUM(price) as total_paid')
    //                         ->where(function($query) use ($type) {
    //                             if( $type == 'order_live' ) {
    //                                 return $query->where('status', 'Live')
    //                                             ->where('payment_status', 'Paid');
    //                             }

    //                             if( $type == 'order_cancel' ) {
    //                                 return $query->where('status', 'Canceled');
    //                             }

    //                             if( $type == 'valid_orders' ) {
    //                                 return $query->whereIn('status', ['Live', 'Processing', 'Content In Writing', 'Content Done', 'Content sent', 'Live in Process', 'Issue']);
    //                             }
    //                         })
    //                         ->where(function($query) use ($sub_buyer_ids, $user_id){
    //                             $UserId[] = $user_id;
    //                             if(count($sub_buyer_ids) > 0) {
    //                                 return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(),$UserId));
    //                             } else{
    //                                 return $query->whereIn('user_id', $UserId);
    //                             }
    //                         })
    //                         ->get();

    //     return isset($total_paid[0]['total_paid']) ? floatval($total_paid[0]['total_paid']) : 0;
    // }

    private function getTotalPurchase($user_id, $type, $year = null, $month = null) {

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        $total_paid = Backlink::selectRaw('SUM(prices) as total_paid')
            ->where(function ($query) use ($type) {
                if ($type == 'order_live') {
                    return $query->where('status', 'Live');
//                        ->where('payment_status', 'Paid');
                }

                if($type == 'order_refund') {
                    return $query->where('status', 'Refund');
                }

                if ($type == 'order_cancel') {
                    return $query->where('status', 'Canceled');
                }

                if ($type == 'valid_orders') {
                    return $query->whereIn('status', [
                        'Live',
                        'Processing',
                        'Content In Writing',
                        'Content Done',
                        'Content sent',
                        'Live in Process',
                        'Issue',
                        'Pending'
                    ]);
                }
            })
            ->where(function ($query) use ($sub_buyer_ids, $user_id) {
                $UserId[] = $user_id;
                if (count($sub_buyer_ids) > 0) {
                    return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(), $UserId));
                } else {
                    return $query->whereIn('user_id', $UserId);
                }
            })
            ->when(isset($year), function($query) use ($year) {
                return $query->whereYear('backlinks.date_process', '=', $year);
            })
            ->when(isset($month), function($query) use ($month) {
                return $query->whereMonth('backlinks.date_process', '=', $month);
            })
            ->get();

        return isset($total_paid[0]['total_paid']) ? floatval($total_paid[0]['total_paid']) : 0;
    }

    public function getTotalRefunded($user_id)
    {
        $total = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
            ->where('user_id', $user_id)
            ->whereIn('admin_confirmation', ['Refunded'])
            ->first();

        return $total ? $total->amount_usd : 0;
    }
}
