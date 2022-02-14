<?php

namespace App\Http\Controllers;

use App\Events\BacklinkLiveSellerEvent;
use App\Events\BacklinkLiveWriterEvent;
use App\Events\BacklinkStatusChangedEvent;
use App\Events\SellerConfirmationEvent;
use App\Events\SellerConfirmedPendingOrderEvent;
use App\Notifications\BacklinkLiveSeller;
use App\Repositories\Contracts\NotificationInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Events\BacklinkLiveEvent;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FollowupSalesController extends Controller
{
    public function getList(Request $request)
    {
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate'] : 50;
        $user = Auth::user();
        $list = Backlink::select('backlinks.*', 'publisher.url as publisher_url', 'B.username as in_charge', 'article.id as article_id')
            ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
            ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
            ->leftJoin('users as A', 'publisher.user_id', '=', 'A.id')
            ->leftJoin('registration', 'A.email', '=', 'registration.email')
            ->leftJoin('users as B', 'registration.team_in_charge', '=', 'B.id')
            ->leftJoin('article', 'backlinks.id', '=', 'article.id_backlink')
            ->where('backlinks.status', '!=', 'To Be Validated')
            ->with([
                'publisher' => function ($query) {
                    $query->with('user:id,name,username')->with('country:id,name');
                }
            ])
            ->with('user:id,name,username')
            ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();

        if ($user->type != 10 && isset($registered->type) && $registered->type == 'Seller') {
            $list->whereIn('backlinks.publisher_id', $publisher_ids);
        }

        if ($user->role_id === 4 && $user->isOurs === 1) {
            $list->whereNotNull('article.id_backlink');
            $list->where(function ($query) use ($user) {
                $query->where('article.id_writer', $user->id)
                    ->orWhere('article.id_writer', null);
            });
        } else {
            if (isset($filter['article']) && !empty($filter['article'])) {
                if ($filter['article'] == 'With') {
                    $list->whereNotNull('article.id_backlink');
                }
                if ($filter['article'] == 'Without') {
                    $list->whereNull('article.id_backlink');
                }
            }
        }

        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
            $list->where('countries.id', $filter['country_id']);
        }

        if (isset($filter['backlink_id']) && !empty($filter['backlink_id'])) {
            $list->where('backlinks.id', $filter['backlink_id']);
        }

        if (isset($filter['in_charge']) && !empty($filter['in_charge'])) {
            $list->where('B.id', $filter['in_charge']);
        }

        if (isset($filter['seller']) && !empty($filter['seller'])) {
            $list->where('publisher.user_id', $filter['seller']);
        }

        if (isset($filter['buyer']) && !empty($filter['buyer'])) {
            $list->where('backlinks.user_id', $filter['buyer']);
        }

        if (isset($filter['status']) && !empty($filter['status'])) {
            if (!is_array($filter['status'])) {
                $list->where('backlinks.status', $filter['status']);
            } else {
                $list->whereIn('backlinks.status', $filter['status']);
            }
        }

        if (isset($filter['search']) && !empty($filter['search'])) {
            $list->where('publisher.url', 'like', '%' . $filter['search'] . '%');
        }

        if (isset($filter['process_date'])) {
            $filter['process_date'] = \GuzzleHttp\json_decode($filter['process_date'], true);
            if (!empty($filter['process_date']) && $filter['process_date']['startDate'] != null) {
                $list->where('date_process', '>=', Carbon::create($filter['process_date']['startDate'])->format('Y-m-d'));
                $list->where('date_process', '<=', Carbon::create($filter['process_date']['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($filter['date_completed'])) {
            $filter['date_completed'] = \GuzzleHttp\json_decode($filter['date_completed'], true);
            if (!empty($filter['date_completed']) && $filter['date_completed']['startDate'] != null) {
                $list->where('live_date', '>=', Carbon::create($filter['date_completed']['startDate'])->format('Y-m-d'));
                $list->where('live_date', '<=', Carbon::create($filter['date_completed']['endDate'])->format('Y-m-d'));
            }
        }

        $data = $list->paginate($paginate);

        return $data;
    }

    public function update(Request $request, NotificationInterface $notification)
    {
        // $seller = DB::table('backlinks')
        //             ->join('publisher','backlinks.publisher_id','=','publisher.id')
        //             ->join('users','publisher.user_id','=','users.id')
        //             ->select('users.id as user_id','users.email as user_primary_email','users.work_mail as user_work_mail')
        //             ->where('backlinks.id',$request->id)
        //             ->first();

        $filename = '';

        if ($request->file != 'undefined') {
            $request->validate([
                'file' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $filename = time() . '-followup.' . $request->file->getClientOriginalExtension();
            move_file_to_storage($request->file, 'images/followup', $filename);
        }

        $input = $request->only('status', 'url_from', 'link_from', 'sales', 'title', 'reason', 'reason_detailed', 'anchor_text');

        // add file to input

        if ($filename) {
            $input['reason_file'] = '/images/followup/' . $filename;
        }

        $backlink = Backlink::findOrFail($request->id);

        $input['payment_status'] = 'Not Paid';
        if ($input['status'] == 'Live') {
            if (empty($request->title) && empty($request->link_from)) {
                return response()->json([
                    "message" => 'Incomplete input',
                    "errors" => [
                        "title" => 'Please add Title',
                        "link_from" => 'Please add Link From'
                    ],
                ], 422);
            }

            if ($backlink->status !== 'Live') {
                // notify buyer
                event(new BacklinkLiveEvent($backlink, $backlink->user));

                // notify seller
                $seller = null;

                if ($backlink->publisher) {
                    $seller = $backlink->publisher->user ?: null;
                }

                if ($seller) {
                    event(new BacklinkLiveSellerEvent($backlink, $seller));
                }

                // notify writer
                $writer = null;

                if ($backlink->article) {
                    if ($backlink->article->status_writer) {
                        if ($backlink->article->status_writer === 'Done') {
                            $writer = $backlink->article->user ?: null;
                        }
                    }
                }

                if ($writer) {
                    event(new BacklinkLiveWriterEvent($backlink, $writer));
                }
            }

            $input['live_date'] = date('Y-m-d');
        } else if ($input['status'] == 'Pending') {
            $input['live_date'] = null;
        } else if ($input['status'] == 'Issue') {
            if ($backlink->article) {
                $backlink->article->update(['status_writer' => 'Issue']);
            }
        } else {
            $input['live_date'] = null;
        }

        if ($backlink->publisher) {
            if ($backlink->publisher->user_id) {
                event(new BacklinkStatusChangedEvent($backlink, $backlink->publisher->user));
            }
        }

        $backlink->update($input);

        return response()->json(['success' => true], 200);
    }

    public function orderConfirmation(Request $request)
    {
        $id = isset($request->code) ? $request->code : null;
        $result = false;
        $backlink = '';

        if (!is_null($id)) {
            if (is_numeric($id)) {
                $backlink = Backlink::find($id);

                if ($backlink) {
                    if ($backlink->status === 'Pending') {

                        // notify cs
                        event(new SellerConfirmedPendingOrderEvent($backlink, 'approve'));

                        $result = true;
                        $backlink->update(['status' => 'Processing']);
                    }
                }
            }
        }

        return response()->json([
            'success' => $result,
            'record' => $backlink
        ], 200);
    }

    public function CancelOrderConfirmation(Request $request)
    {
        $id = isset($request->code) ? $request->code : null;
        $result = false;
        $backlink = '';

        if (!is_null($id)) {
            if (is_numeric($id)) {
                $backlink = Backlink::find($id);

                if ($backlink) {
                    if ($backlink->status === 'Pending') {

                        // notify cs
                        event(new SellerConfirmedPendingOrderEvent($backlink, 'cancel'));

                        $result = true;
                        $backlink->update([
                            'status' => 'Canceled',
                            'reason' => 'Other',
                            'reason_detailed' => 'automated canceled via Email'
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'success' => $result,
            'record' => $backlink
        ], 200);
    }

    public function processPendingOrder(Request $request)
    {
        $id = isset($request->id) ? $request->id : null;

        if (!is_null($id)) {
            if (is_numeric($id)) {
                $backlink = Backlink::find($id);

                if ($backlink) {
                    if ($backlink->status === 'Pending') {

                        // notify cs
                        event(new SellerConfirmedPendingOrderEvent($backlink, $request->process));

                        if ($request->process === 'approve') {
                            $backlink->update(['status' => 'Processing']);
                        } else {
                            $backlink->update([
                                'status' => 'Canceled',
                                'reason' => 'Other',
                                'reason_detailed' => 'Order details not correct, cancelled by seller'
                            ]);
                        }
                    }
                }
            }
        }
    }
}
