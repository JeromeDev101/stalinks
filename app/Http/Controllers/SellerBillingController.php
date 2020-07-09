<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\Billing;
use App\Models\WalletTransaction;

class SellerBillingController extends Controller
{
    public function getList(Request $request) {
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
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

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

        foreach( $ids as $id ){
            $backlink_id = explode('-',$id)[0];
            $user_id_seller = explode('-',$id)[1];
            $seller_price = explode('-',$id)[2];

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

        // update wallet transaction
        // foreach( $ids as $id ) {
        //     $backlink_id = explode('-',$id)[0];
        // }

        return response()->json(['success' => true], 200);
    }
}
