<?php

namespace App\Http\Controllers;

use App;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Http\Request;
use App\Models\LinkInjection;
use Illuminate\Support\Facades\Auth;
use App\Events\LinkInjectionRequestEvent;
use App\Http\Requests\LinkInjectionRequest;
use App\Notifications\LinkInjectionChecked;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LinkInjectionPurchased;
use App\Http\Requests\LinkInjectionRequestSeller;
use App\Notifications\LinkInjectionStatusChanged;

class LinkInjectionController extends Controller
{
    public function index (Request $request) {
        $filter = $request->all();

        $injections = LinkInjection::select('link_injections.*', 'publisher.url as publisher_url')
        ->leftJoin('publisher', 'link_injections.publisher_id', '=', 'publisher.id')
        ->orderBy('created_at', 'desc')
        ->with('buyer.UserType')
        ->with('publisher.user');

        if (Auth::user()->role_id == 6) {
            $injections = $injections->where('publisher.user_id', Auth::user()->id);
        }

        if (Auth::user()->role_id == 5) {
            $injections = $injections->where('link_injections.buyer_id', Auth::user()->id);
        }

        if (isset($filter['url_publisher']) && !empty($filter['url_publisher'])) {
            $injections = $injections->where('publisher.url', 'LIKE', '%'. $filter['url_publisher'] .'%');
        }

        if (isset($filter['url_article']) && !empty($filter['url_article'])) {
            $injections = $injections->where('link_injections.url_article', 'LIKE', '%'. $filter['url_article'] .'%');
        }

        if (isset($filter['url_advertiser']) && !empty($filter['url_advertiser'])) {
            $injections = $injections->where('link_injections.url_advertiser', 'LIKE', '%'. $filter['url_advertiser'] .'%');
        }

        if (isset($filter['link_to']) && !empty($filter['link_to'])) {
            $injections = $injections->where('link_injections.link', 'LIKE', '%'. $filter['link_to'] .'%');
        }

        if (isset($filter['anchor_text']) && !empty($filter['anchor_text'])) {
            $injections = $injections->where('link_injections.anchor_text', 'LIKE', '%'. $filter['anchor_text'] .'%');
        }

        if( isset($request->paginate) && $request->paginate == 'All' ){
            $injections = $injections->get();

            return response()->json([
                'data' => $injections,
                'total' => $injections->count(),
            ],200);
        }else{
            $injections = $injections->paginate($request->paginate);

            return response()->json($injections);
        }
    }

    public function request (LinkInjectionRequest $request) {
        $publisher = Publisher::find($request->publisher_id ? $request->publisher_id : $request->id);

        $url_advertiser = $request->url_advertiser;

        $injection = LinkInjection::create([
            'injection_price' => $request->injection_price,
            'url_article' => $request->url_article,
            'url_advertiser' => $url_advertiser,
            'link' => $request->link,
            'anchor_text' => $request->anchor_text,
            'publisher_id' => $publisher->id,
            'buyer_id' => Auth::user()->id,
            'status' => 'Pending',
            'date_requested' => date('Y-m-d'),
        ]);

        $seller_account = null;

        if ($injection->publisher) {
            $seller_account = $injection->publisher->user ?: null;
        }

        if ($seller_account) {
            event(new LinkInjectionRequestEvent($injection, $seller_account));
        }

        return response()->json(['success' => true], 200);
    }

    public function update (Request $request) {
        $filename = '';

        if ($request->file != 'undefined' && isset($request->file)) {
            $request->validate([
                'file' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $filename = time() . '-followup.' . $request->file->getClientOriginalExtension();
            move_file_to_storage($request->file, 'images/injection', $filename);
        }

        $input = $request->except('id', 'publisher_url', 'file');
        $injection = LinkInjection::findOrFail($request->id);
        $team = User::whereIn('role_id', [8,1])->where('isOurs', 0)->where('status', 'active')->get();

        if ($filename) {
            $input['reason_file'] = '/images/injection/' . $filename;
        }

        if ($input['status'] == 'Issue') {
            if ($injection->status !== 'Issue') {
                $injection->buyer->notify(new LinkInjectionStatusChanged($injection->buyer, $injection, 'buyer', 'issue'));
                Notification::send($team, new LinkInjectionStatusChanged($injection->buyer, $injection, 'team', 'issue'));
            }
        } else if ($input['status'] == 'Canceled') {
            if ($injection->status !== 'Canceled') {
                $injection->buyer->notify(new LinkInjectionStatusChanged($injection->buyer, $injection, 'buyer', 'canceled'));
                Notification::send($team, new LinkInjectionStatusChanged($injection->buyer, $injection, 'team', 'canceled'));
            }
        } else if ($input['status'] == 'Live') {
            if ($injection->status !== 'Live') {
                $injection->buyer->notify(new LinkInjectionStatusChanged($injection->buyer, $injection, 'buyer', 'live'));
                Notification::send($team, new LinkInjectionStatusChanged($injection->buyer, $injection, 'team', 'live'));

                $input['live_date'] = date('Y-m-d');
            }
        }

        $injection->update($input);

        return response()->json(['success' => true], 200);
    }

    public function approve (LinkInjectionRequestSeller $request) {
        $injection = LinkInjection::where('id', $request->id)->first();

        $injection->update([
            'injection_price' => $request->injection_price,
            'buyer_injection_price' => $request->buyer_injection_price,
            'status' => 'Checked',
            'date_process' => date('Y-m-d'),
        ]);

        $injection->buyer->notify(new LinkInjectionChecked($injection->buyer, $injection, 'buyer'));

        $team = User::whereIn('role_id', [8,1])->where('isOurs', 0)->where('status', 'active')->get();

        Notification::send($team, new LinkInjectionChecked($injection->buyer, $injection, 'team'));

        return response()->json(['success' => true], 200);
    }

    public function purchase (Request $request) {
        $injection = LinkInjection::where('id', $request->id)->first();

        $injection->update([
            'status' => 'Processing'
        ]);

        // notify seller

        $seller_account = null;

        if ($injection->publisher) {
            $seller_account = $injection->publisher->user ?: null;
        }

        if ($seller_account) {
            $seller_account->notify(new LinkInjectionPurchased($seller_account, $injection, 'seller'));
        }

        $team = User::whereIn('role_id', [8,1])->where('isOurs', 0)->where('status', 'active')->get();

        Notification::send($team, new LinkInjectionPurchased($seller_account, $injection, 'team'));

        return response()->json(['success' => true], 200);
    }
}