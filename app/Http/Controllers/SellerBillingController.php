<?php

namespace App\Http\Controllers;

use App\Events\BuyerDebited;
use App\Events\SellerPaid;
use App\Http\Requests\SellerPayRequest;
use App\Repositories\Contracts\NotificationInterface;
use App\Repositories\Contracts\PaypalInterface;
use App\Repositories\Traits\NotificationTrait;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\Billing;
use App\Models\TotalWallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class SellerBillingController extends Controller
{
    use NotificationTrait;

    public function getList(Request $request) {
        $filter = $request->all();

        $columns = [
            'backlinks.*',
            'billing.date_billing',
            'billing.proof_doc_path',
            'billing.admin_confirmation',
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('billing', 'backlinks.id', '=', 'billing.id_backlink')
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->with(['publisher' => function($q){
                        $q->with('user:id,name,username');
                    }])
                    ->with('user:id,name,username')
                    ->whereIn('status', ['Live','Live in Process']);

        if( isset($filter['status_billing']) && !empty($filter['status_billing']) ){
            if( $filter['status_billing'] == 'Done'){
                $list = $list->where('billing.admin_confirmation', '=', '1');
            }else{
                $list = $list->whereNull('billing.admin_confirmation');
            }
        }

        if (isset($filter['date_completed'])) {
            $filter['date_completed'] = json_decode($filter['date_completed']);
        }

        if( isset($filter['date_completed']) && !empty($filter['date_completed']) && $filter['date_completed']->startDate != ''){
            $list->where('live_date', '>=', Carbon::create($filter['date_completed']->startDate)
                ->format('Y-m-d'));
            $list->where('live_date', '<=', Carbon::create($filter['date_completed']->endDate)
                ->format('Y-m-d'));
        }

        if (isset($filter['date_of_payment'])) {
            $filter['date_of_payment'] = json_decode($filter['date_of_payment']);
        }

        if( isset($filter['date_of_payment']) && !empty($filter['date_of_payment']) && $filter['date_of_payment']->startDate != ''){
            $list->where('billing.date_billing', '>=', Carbon::create($filter['date_of_payment']->startDate)
                ->format('Y-m-d'));
            $list->where('billing.date_billing', '<=', Carbon::create($filter['date_of_payment']->endDate)
                ->format('Y-m-d'));
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list = $list->where('publisher.user_id', $filter['seller']);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('backlinks.id', $filter['search']);
        }

        $list->orderBy('created_at', 'desc');
        return [
            'data' => $list->get(),
        ];
    }

    public function payBilling(SellerPayRequest $request, NotificationInterface $notification, PaypalInterface $paypal, InvoiceService $invoice) {
        // Disregard file upload if payment type is paypal
        if ($request->get('payment_id') != 1) {
            $filename = time() . '-billing.' . $request->file->getClientOriginalExtension();
            move_file_to_storage($request->file, 'images/billing', $filename);
        } else {
            $filename = null;
        }

        $paypalResult = null;
        $payoutResult = null;

        DB::transaction(function () use ($request, $paypal, &$paypalResult, &$payoutResult, $invoice, $filename) {
            $ids = json_decode($request->ids);

            $seller = '';
            $backlink_ids = [];
            $totalBacklinkAmount = 0;
            foreach( $ids as $data ){
                $backlink_id = $data->id;
                $seller = User::find($data->publisher->user->id);
                $seller_price = $data->publisher->price;
                $backlink_ids[] = $backlink_id;
                $totalBacklinkAmount += $seller_price;

                $backlink = Backlink::find($backlink_id);
                $backlink->update(['payment_status' => 'Paid']);

                $billing = Billing::create([
                    'id_backlink' => $backlink_id,
                    'id_user' => $seller->id,
                    'seller_price' => $seller_price,
                    'id_payment_via' => intVal($request->payment_id),
                    'date_billing' => date('Y-m-d'),
                    'proof_doc_path' => '/images/billing/' . $filename,
                    'admin_confirmation' => 1
                ]);
            }

            // update the total wallet of a buyer
            $total = TotalWallet::select('user_id')->get();
            foreach( $total as $buyer ) {
                $amount_paid = $this->getTotal($backlink_ids, $buyer->user_id);

                if($amount_paid != ""){
                    $wallet = TotalWallet::where('user_id',$buyer->user_id)->first();
                    $total_amount = floatval($wallet['total_wallet']) - floatval($amount_paid);
                    $wallet->update(['total_wallet' => $total_amount]);
                }
            }

            if ($request->get('payment_id') == 1) {
                $paypalResult = $paypal->createPayout([
                    'email' => $seller->email,
                    'amount' => $totalBacklinkAmount
                ]);

                $payoutResult = $paypal->fetchPayout($paypalResult->result->batch_header->payout_batch_id);

                Billing::where('id_backlink', $ids[0]->id)->update([
                    'fee' => $payoutResult->result->items[0]->payout_item_fee->value
                ]);

                $invoicePdf = $invoice->generateSellerProof($seller, $backlink_ids, $totalBacklinkAmount, $billing->id, $payoutResult->result->items[0]->payout_item_fee->value);

                $billing->update([
                    'proof_doc_path' =>  $invoicePdf
                ]);
            }

            $this->buyerDebittedNotification($backlink, $totalBacklinkAmount, $backlink_ids);
            $this->sellerPaidNotification($seller->id, $totalBacklinkAmount, $backlink_ids);
        });

        return response()->json(['success' => true, 'data' => $payoutResult], 200);
    }

    private function getTotal($backlink_ids = [], $buyer_id){
        $backlink = Backlink::selectRaw('SUM(price) as total_amount')
                            ->whereIn('id', $backlink_ids)
                            ->where('status', 'Live')
                            ->where('payment_status', 'Paid')
                            ->where('user_id', $buyer_id)
                            ->get();
        return $backlink[0]->total_amount;
    }

    public function getSellerInfo(Request $request) {
        $seller_id = [];
        $result = [
            'data' => [],
            'success' => false
        ];
        foreach($request->ids as $data){
            if( isset($data['publisher']) && isset($data['publisher']['user'])){
                $seller_id[] = $data['publisher']['user']['id'];
            }
        }

        $check = array_unique($seller_id);

        if( count($check) > 1){
            return $result;
        }

        if( isset($check[0]) ){
            $seller_info = User::where('id', $check[0])->with(['paymentType', 'registration'])->get();

            $result = [
                'data' => $seller_info,
                'success' => true
            ];
        }

        return $result;
    }

    public function downloadPaypalProof($id)
    {
        $file = Storage::get('STAL-SELLER-' . $id . '.pdf');

        return $file;
    }
}
