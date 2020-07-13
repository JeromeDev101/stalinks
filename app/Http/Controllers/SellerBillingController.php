<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\Billing;
use App\Models\TotalWallet;

class SellerBillingController extends Controller
{
    public function getList(Request $request) {
        $filter = $request->all();

        $columns = [
            'backlinks.*',
            'billing.proof_doc_path',
            'billing.admin_confirmation',
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('billing', 'backlinks.id', '=', 'billing.id_backlink')
                    ->with(['publisher' => function($q){
                        $q->with('user:id,name');
                    }])
                    ->with('user:id,name')
                    ->where('status', 'Live');

        if( isset($filter['status_billing']) && !empty($filter['status_billing']) ){
            if( $filter['status_billing'] == 'Done'){
                $list = $list->where('admin_confirmation', '=', '1');
            }else{
                $list = $list->where('admin_confirmation', '!=','1');
            }    
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('backlinks.id', $filter['search']);
        }
                    
        $list->orderBy('created_at', 'desc');
        return [
            'data' => $list->get(),
        ];
    }

    public function payBilling(Request $request) {
        $request->validate([
            'payment_type' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ids = explode("," , $request->ids);

        $image = $request->file;
        $new_name = time() . '-billing.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/billing'), $new_name);
        $backlink_ids = [];
        foreach( $ids as $id ){
            $backlink_id = explode('-',$id)[0];
            $user_id_seller = explode('-',$id)[1];
            $seller_price = explode('-',$id)[2];
            $backlink_ids[] = $backlink_id;

            $backlink = Backlink::find($backlink_id);
            $backlink->update(['payment_status' => 'Paid']);

            Billing::create([
                'id_backlink' => $backlink_id,
                'id_user' => $user_id_seller,
                'seller_price' => $seller_price,
                'id_payment_via' => $request->payment_type,
                'date_billing' => date('Y-m-d'),
                'proof_doc_path' => '/images/billing/'.$new_name,
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

        return response()->json(['success' => true], 200);
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
}
