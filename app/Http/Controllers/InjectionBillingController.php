<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinkInjection;
use App\Models\BillingInjection;
use App\Models\User;
use Carbon\Carbon;

class InjectionBillingController extends Controller
{
    public function getList(Request $request) {
        $date_created = isset($request->date_created) ? json_decode($request->date_created):null;
        $date_completed = isset($request->date_completed) ? json_decode($request->date_completed):null;

        $link_injection = LinkInjection::where('status', 'Live')
            ->when($request->search_injection != '', function($query) use ($request) {
                return $query->where('id', $request->search_injection);
            })
            ->when($request->status != '' , function($query) use ($request) {
                return $query->where('payment_status', $request->status);
            })
            ->when(isset($date_completed->startDate) && $date_completed->startDate != '' , function($query) use ($date_completed) {
                return $query->whereDate('date_process', '>=', Carbon::create($date_completed->startDate)->format('Y-m-d'))
                        ->whereDate('date_process', '<=', Carbon::create($date_completed->endDate)->format('Y-m-d'));
            })
            ->when(isset($date_created->startDate) && $date_created->startDate != '' , function($query) use ($date_created) {
                return $query->whereDate('date_requested', '>=', Carbon::create($date_created->startDate)->format('Y-m-d'))
                        ->whereDate('date_requested', '<=', Carbon::create($date_created->endDate)->format('Y-m-d'));
            })
            ->with(['publisher.user'])
            ->with('billing')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($link_injection, 200);
    }

    public function sellerListInjection() {
        $link_injection_sellers = LinkInjection::where('status', 'Live')
                            ->with(['publisher.user'])
                            ->get();
        
        $sellers = [];
        foreach($link_injection_sellers as $index => $seller) {
            $sellers[$index]['name'] = $seller->publisher->user->username;
            $sellers[$index]['id'] = $seller->publisher->user->id;
        }

        return response()->json($sellers, 200);
    }

    public function sellerInfoInjection(Request $request) {
        $seller_id = [];
        $result = [
            'data' => [],
            'success' => false
        ];

        $test= [];
        foreach($request->ids as $data){
            $data = json_decode($data, true);

            if( isset($data['publisher']) && isset($data['publisher']['user'])){
                $seller_id[] = $data['publisher']['user']['id'];
            }
        }

        $check = array_unique($seller_id);

        if( count($check) > 1){
            return $result;
        }

        if( isset($check[0]) ){
            $seller_info = User::where('id', $check[0])->with(['paymentType', 'registration', 'userPaymentTypes'])->get();

            $result = [
                'data' => $seller_info,
                'success' => true
            ];
        }

        return $result;
    } 

    public function paySellerInjection(Request $request) {
        // dd($request->all(), json_decode($request->ids, true) );

        $datas = json_decode($request->ids, true);

        $filename = time() . '-billing.' . $request->file->getClientOriginalExtension();
        move_file_to_storage($request->file, 'images/billing', $filename);

        foreach($datas as $data) {
            $inputs['id_injection'] = $data['id'];
            $inputs['id_user'] = $data['buyer_id'];
            $inputs['buyer_price'] = $data['buyer_injection_price'];
            $inputs['seller_price'] = $data['injection_price'];
            $inputs['commission'] = intval($data['buyer_injection_price']) - intval($data['injection_price']);
            $inputs['id_payment_via'] = $request->payment_id;
            $inputs['proof_doc_path'] = '/images/billing/' . $filename;

            $link_injection = LinkInjection::find($data['id']);
            if($link_injection) {
                $link_injection->update(['payment_status' => 'paid']);
            }

            BillingInjection::create($inputs);
        }

        return response()->json(['success' => true], 200);
    }
}
