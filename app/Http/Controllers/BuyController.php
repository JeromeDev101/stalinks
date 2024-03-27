<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Events\BuyEvent;
use App\Models\Backlink;
use App\Models\Publisher;
use App\Models\Registration;
use App\Models\BacklinksInterested;
use App\Services\HttpClient;
use Illuminate\Http\Request;
use App\Models\BuyerPurchased;
use App\Events\NotificationEvent;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use App\PublisherWithComputedPrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Traits\BuyTrait;
use App\Events\SellerConfirmationEvent;
use App\Events\SellerReceivesOrderEvent;
use App\Events\BacklinkStatusChangedEvent;
use App\Repositories\Traits\NotificationTrait;
use App\Repositories\Contracts\NotificationInterface;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use App\Repositories\Contracts\PublisherRepositoryInterface;

class BuyController extends Controller
{
    use BuyTrait, NotificationTrait;

    protected $publisherRepository, $backlinkRepository, $httpClient;

    public function __construct(PublisherRepositoryInterface $publisherRepository, BackLinkRepositoryInterface $backLinkRepository, HttpClient $httpClient)
    {
        $this->publisherRepository = $publisherRepository;
        $this->backlinkRepository = $backLinkRepository;
        $this->httpClient = $httpClient;
    }

    public function getList(Request $request)
    {
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate'])) ? $filter['paginate'] : 50;
        $user = Auth::user();

        $credit = 0;

        // get the necessary values to calculate the computed price
        $formulaData = $this->getFormulaData();

        $percent = $formulaData->percentage;
        $additional = $formulaData->additional;
        $commission = 'yes';
        $buyer_type_basic = $formulaData->basic;
        $buyer_type_medium = $formulaData->medium;
        $buyer_type_premium = $formulaData->premium;
        $buyer_type = null;

        $registration = Auth::user()->registration;

        if ($registration) {
            $buyer_type = $registration->buyer_type;
            $commission = strtolower($registration->commission);

            if ($buyer_type == 'Basic') {
                $percent = $buyer_type_basic;
            } else if ($buyer_type == 'Medium') {
                $percent = $buyer_type_medium;
            } else {
                $percent = $buyer_type_premium;
            }
        }

        $columns = [
            'publisher.*',
            'registration.username',
            'users.name',
            'users.username as user_name',
            'users.isOurs',
            'registration.company_name',
            'registration.is_recommended',
            'countries.name AS country_name',
            'country_continent.name AS country_continent',
            'publisher_continent.name AS publisher_continent',
            'languages.name AS language_name',
            'buyer_purchased.status as status_purchased',
            'backlinks_interesteds.url_advertiser as interested_domain_name',
            DB::raw('org_keywords/org_traffic as ratio_value'),
            DB::raw('ROUND(CASE
                WHEN inc_article = "yes" AND "'.$commission.'" = "no" THEN price
                WHEN inc_article = "yes" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price)
                WHEN inc_article = "no" AND "'.$commission.'" = "no" THEN price + '.$additional.'
                WHEN inc_article = "no" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price) + '.$additional.'
                ELSE price
            END) AS computed_price')
        ];

        $user_id = $user->id;
        $backlink_interested_user = [];

        $backlink_interested_user[] = $user_id;

        // check sub buyers
        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

        if (count($sub_buyer_ids)) {
            $backlink_interested_user = array_merge($backlink_interested_user, $sub_buyer_ids->toArray());
        }

        // check if sub account
        $registered = Registration::where('email', Auth::user()->email)->first();
        if (isset($registered->is_sub_account) && $registered->is_sub_account == 1) {
            if (isset($registered->team_in_charge)) {
                $backlink_interested_user = [];
                $user_model = User::where('id', $registered->team_in_charge)->first();
                $backlink_interested_user[] = isset($user_model->id) ? $user_model->id : Auth::user()->id;
                $backlink_interested_user[] = $user_id;
            }
        }

        $list = Publisher::select($columns)
            ->with(['backlinks_interested' => function ($query) use ($backlink_interested_user) {
                $orderedUserIds = implode(',', $backlink_interested_user);
                $query->whereIn('user_id', $backlink_interested_user)
                    ->orderByRaw("FIELD(user_id, $orderedUserIds)");
            }])
            ->with(['buyer_purchased.buyer.registration' => function ($q) use ($user, $filter){
                if (isset($filter['status_purchase_mode']) && !empty($filter['status_purchase_mode'])) {
                    if ($filter['status_purchase_mode'] === 'Team') {
                        $user_id = $user->id;

                        // check if sub account
                        $registered = Registration::where('email', Auth::user()->email)->first();
                        if (isset($registered->is_sub_account) && $registered->is_sub_account == 1) {
                            if (isset($registered->team_in_charge)) {
                                $user_model = User::where('id', $registered->team_in_charge)->first();
                                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
                            }
                        }

                        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
                        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

                        $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                            ->where(function($query) use ($user_id, $sub_buyer_ids) {
                                $query->where('buyer_purchased.user_id_buyer', $user_id)
                                ->orWhereIn('buyer_purchased.user_id_buyer', $sub_buyer_ids);
                            })
                            ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                    } else {
                        $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->where('buyer_purchased.user_id_buyer', $user->id)
                        ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                    }
                } else {
                    $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                    ->where('buyer_purchased.user_id_buyer', $user->id)
                    ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                }
            }])
            ->leftJoin('backlinks_interesteds', function ($q) use ($user_id, $backlink_interested_user, $filter) {
                if (isset($filter['interested_domain_name']) && !empty($filter['interested_domain_name'])) {
                    $q->on('publisher.id', '=', 'backlinks_interesteds.publisher_id')
                    ->whereIn('backlinks_interesteds.user_id', $backlink_interested_user);
                } else {
                    $q->on('publisher.id', '=', 'backlinks_interesteds.publisher_id')
                    ->where('backlinks_interesteds.user_id', $user_id);
                }
            })
            ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->leftJoin('countries', 'publisher.country_id', '=', 'countries.id')
            ->leftJoin('continents as country_continent', 'countries.continent_id', '=', 'country_continent.id')
            ->leftJoin('continents as publisher_continent', 'publisher.continent_id', '=', 'publisher_continent.id')
            ->leftJoin('languages', 'publisher.language_id', '=', 'languages.id')
            ->leftJoin('buyer_purchased', function ($q) use ($user, $filter) {

                if (isset($filter['status_purchase_mode']) && !empty($filter['status_purchase_mode'])) {
                    if ($filter['status_purchase_mode'] === 'Team') {
                        $user_id = $user->id;

                        // check if sub account
                        $registered = Registration::where('email', Auth::user()->email)->first();
                        if (isset($registered->is_sub_account) && $registered->is_sub_account == 1) {
                            if (isset($registered->team_in_charge)) {
                                $user_model = User::where('id', $registered->team_in_charge)->first();
                                $user_id = isset($user_model->id) ? $user_model->id : Auth::user()->id;
                            }
                        }

                        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user_id)->pluck('email');
                        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');

                        $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                            ->where(function($query) use ($user_id, $sub_buyer_ids) {
                                $query->where('buyer_purchased.user_id_buyer', $user_id)
                                ->orWhereIn('buyer_purchased.user_id_buyer', $sub_buyer_ids);
                            })
                            ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                    } else {
                        $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                        ->where('buyer_purchased.user_id_buyer', $user->id)
                        ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                    }
                } else {
                    $q->on('publisher.id', '=', 'buyer_purchased.publisher_id')
                    ->where('buyer_purchased.user_id_buyer', $user->id)
                    ->where('buyer_purchased.id', '=', DB::raw('(SELECT MAX(id) FROM buyer_purchased AS bp WHERE bp.publisher_id = buyer_purchased.publisher_id)'));
                }
            })
            // ->whereNotNull('users.id') // to remove all seller's URL that deleted
            ->has('user')
            ->where('registration.account_validation', 'valid')
            ->where('publisher.valid', 'valid')
            ->where('publisher.qc_validation', 'yes')
            ->whereNotNull('publisher.href_fetched_at');

        // if( Auth::user()->role_id == 5 || (isset($registered->type) && $registered->type == 'Buyer') ){
        //     $list->where('publisher.valid', 'valid');
        // }

        if (isset($filter['casino_sites']) && !empty($filter['casino_sites'])) {
            $list->where('publisher.casino_sites', $filter['casino_sites']);
        }

        if (isset($filter['seller']) && !empty($filter['seller'])) {
            $list->where('publisher.user_id', $filter['seller']);
        }

        if (isset($filter['is_https'])) {
            $list->where('publisher.is_https', $filter['is_https']);
        }

        if (isset($filter['search']) && !empty($filter['search'])) {
            // remove http
            $url = remove_http($filter['search']);
            // remove space
            $url = trim($url, " ");
            // remove /
            $url = rtrim($url,"/");

            $list->where('publisher.url', 'like', '%' . $url . '%');
        }

        if (isset($filter['interested_domain_name']) && !empty($filter['interested_domain_name'])) {
            // remove http
            $url = remove_http($filter['interested_domain_name']);
            // remove space
            $url = trim($url, " ");
            // remove /
            $url = rtrim($url,"/");

            $list->where('backlinks_interesteds.url_advertiser', 'like', '%' . $url . '%');
        }

        if (isset($filter['language_id']) && !empty($filter['language_id'])) {
            if (is_array($filter['language_id'])) {
                $list->whereIn('publisher.language_id', $filter['language_id']);
            } else {
                $list->where('publisher.language_id', $filter['language_id']);
            }
        }

        if (isset($filter['status_purchase']) && !empty($filter['status_purchase'])) {
            if (is_array($filter['status_purchase'])) {
                if (in_array('New', $filter['status_purchase'])) {
                    $list->where(function ($query) use ($filter) {
                        $query->whereNull('buyer_purchased.publisher_id');
                        $query->orWhereIn('buyer_purchased.status', $filter['status_purchase']);
                    });
                } else {
                    $list->whereIn('buyer_purchased.status', $filter['status_purchase']);
                }
            } else {
                if ($filter['status_purchase'] === 'New') {
                    $list->whereNull('buyer_purchased.publisher_id');
                } else {
                    $list->where('buyer_purchased.status', $filter['status_purchase']);
                }
            }
        }

        if (isset($filter['country_id']) && !empty($filter['country_id'])) {
            if (is_array($filter['country_id'])) {
                $list->whereIn('publisher.country_id', $filter['country_id']);
            } else {
                $list->where('publisher.country_id', $filter['country_id']);
            }
        }

        if (isset($filter['continent_id']) && !empty($filter['continent_id'])) {
            if (is_array($filter['continent_id'])) {
                $list = $list->where(function ($query) use ($filter) {
                    if (($key = array_search(0, $filter['continent_id'])) !== false) {
                        unset($filter['continent_id'][$key]);
                        $query->orWhere(function ($subs) {
                            $subs->orWhere('publisher.continent_id', null)
                                ->where('publisher.country_id', null);
                        });
                    }

                    if (!empty($filter['continent_id'])) {
                        $query->orWhereIn('countries.continent_id', $filter['continent_id'])
                            ->orWhereIn('publisher.continent_id', $filter['continent_id']);
                    }
                });
            } else {
                $list = $list->where(function ($query) use ($filter) {
                    $query->where('countries.continent_id', $filter['continent_id'])
                        ->orWhere('publisher.continent_id', $filter['continent_id']);
                });
            }
        }

        if (isset($filter['domain_zone']) && !empty($filter['domain_zone'])) {
            if (is_array($filter['domain_zone'])) {
//                $regs = implode("|", $filter['domain_zone']);
                $regs = implode(",", $filter['domain_zone']);
                $regs = str_replace('.', '', $regs);
                $regs = explode(",", $regs);

//                $list = $list->whereRaw("REPLACE(SUBSTRING_INDEX(url, '.', -1),' ','') REGEXP '" . $regs . "'");

                $list = $list->whereIn(DB::raw("REPLACE(REPLACE(SUBSTRING_INDEX(url, '.', -1),' ',''),'/','')"), $regs);
            } else {
                $regs = str_replace('.', '', $filter['domain_zone']);

//                $list = $list->whereRaw("REPLACE(SUBSTRING_INDEX(url, '.', -1),' ','') like '%" . $regs . "%'");
                $list = $list->whereRaw("REPLACE(REPLACE(SUBSTRING_INDEX(url, '.', -1),' ',''),'/','') = '$regs'");
            }
        }

        if (isset($filter['ur']) && !empty($filter['ur'])) {
            if ($filter['ur_direction'] === 'Above') {
                $list->where('publisher.ur', '>=', intval($filter['ur']));
            } else {
                $list->where('publisher.ur', '<=', intval($filter['ur']));
            }
        }

        if (isset($filter['dr']) && !empty($filter['dr'])) {
            if ($filter['dr_direction'] === 'Above') {
                $list->where('publisher.dr', '>=', intval($filter['dr']));
            } else {
                $list->where('publisher.dr', '<=', intval($filter['dr']));
            }
        }

        if (isset($filter['org_kw']) && !empty($filter['org_kw'])) {
            if ($filter['org_kw_direction'] === 'Above') {
                $list->where('publisher.org_keywords', '>=', intval($filter['org_kw']));
            } else {
                $list->where('publisher.org_keywords', '<=', intval($filter['org_kw']));
            }
        }

        if (isset($filter['org_traffic']) && !empty($filter['org_traffic'])) {
            if ($filter['org_traffic_direction'] === 'Above') {
                $list->where('publisher.org_traffic', '>=', intval($filter['org_traffic']));
            } else {
                $list->where('publisher.org_traffic', '<=', intval($filter['org_traffic']));
            }
        }

        if (isset($filter['price']) && !empty($filter['price'])) {
            if ($filter['price_direction'] === 'Above') {
                if (Auth::user()->role_id == 5) {
                    $list->whereRaw('ROUND(CASE
                        WHEN inc_article = "yes" AND "'.$commission.'" = "no" THEN price
                        WHEN inc_article = "yes" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price)
                        WHEN inc_article = "no" AND "'.$commission.'" = "no" THEN price + '.$formulaData->additional.'
                        WHEN inc_article = "no" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price) + '.$formulaData->additional.'
                        ELSE price
                    END) >= ?', [intval($filter['price'])]);
                } else {
                    $list->where('publisher.price', '>=', intval($filter['price']));
                }
            } else {
                if (Auth::user()->role_id == 5) {
                    $list->whereRaw('ROUND(CASE
                        WHEN inc_article = "yes" AND "'.$commission.'" = "no" THEN price
                        WHEN inc_article = "yes" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price)
                        WHEN inc_article = "no" AND "'.$commission.'" = "no" THEN price + '.$formulaData->additional.'
                        WHEN inc_article = "no" AND "'.$commission.'" = "yes" THEN price + ROUND(('.$percent.' / 100) * price) + '.$formulaData->additional.'
                        ELSE price
                    END) <= ?', [intval($filter['price'])]);
                } else {
                    $list->where('publisher.price', '<=', intval($filter['price']));
                }
            }
        }

        if (isset($filter['price_basis']) && !empty($filter['price_basis'])) {
            if (is_array($filter['price_basis'])) {
                $list->whereIn('publisher.price_basis', $filter['price_basis']);
            } else {
                $list->where('publisher.price_basis', $filter['price_basis']);
            }
        }

        if (isset($filter['code'])) {
            if (is_array($filter['code'])) {
                $list->where(function ($q) use ($filter) {
                    foreach ($filter['code'] as $key => $code) {
                        if ($key == 0) {
//                            $q->whereRaw('ROUND(
//                                (
//                                LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
//                                ) / LENGTH("A")) = ' . rtrim($code, 'A'));

                            $q->whereRaw('ROUND(
                                (
                                LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "' . str_split($code)[1] . '", "") )
                                ) / LENGTH("' . str_split($code)[1] . '")) = ' . str_split($code)[0]);
                        } else {
                            $q->where(function ($query) use ($code) {
//                                $query->whereRaw('ROUND(
//                                    (
//                                    LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
//                                    ) / LENGTH("A")) = ' . rtrim($code, 'A'));
                                $query->whereRaw('ROUND(
                                    (
                                    LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "' . str_split($code)[1] . '", "") )
                                    ) / LENGTH("' . str_split($code)[1] . '")) = ' . str_split($code)[0]);
                            });
                        }
                    }
                });
            } else {
                $list->whereRaw('ROUND(
                    (
                    LENGTH(publisher.code_comb)- LENGTH( REPLACE (publisher.code_comb, "A", "") )
                    ) / LENGTH("A")) = ' . rtrim($filter['code'], 'A'));
            }
        }

        if (isset($filter['topic']) && !empty($filter['topic'])) {
            if (is_array($filter['topic'])) {
                $list->where(function ($query) use ($filter) {
                    $ctr = 0;
                    foreach ($filter['topic'] as $topic) {
                        // $list = $list->where('publisher.topic', 'like', '%'.$topic.'%');
                        if ($ctr == 0) {
                            $query->where('publisher.topic', 'like', '%' . $topic . '%');
                        } else {
                            $query->orWhere('publisher.topic', 'like', '%' . $topic . '%');
                        }
                        $ctr++;
                    }
                });
            } else {
                $list = $list->where('publisher.topic', 'like', '%' . $filter['topic'] . '%');
            }
        }

        if (isset($filter['sort']) && !empty($filter['sort'])) {
            foreach ($filter['sort'] as &$sort) {
                $sort = \GuzzleHttp\json_decode($sort);
                if ($sort->column === 'interested_domain_name') {
                    $list = $list->orderBy('interested_domain_name', $sort->sort);
                } else if ($sort->column === 'computed_price') {
                    $list = $list->orderBy('computed_price', $sort->sort);
                } else {
                    $list = $list->orderByRaw("$sort->column $sort->sort");
                }
            }
        } else {
            $list->orderBy('created_at', 'desc');
        }

        if (isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All') {
            $result = $list->orderBy('id', 'desc')->get();
        } else {
            $result = $list->paginate($paginate);
        }

        // Getting credit left
        if (isset($user->registration->is_sub_account) && $user->registration->is_sub_account == 1) {
            if (isset($user->registration->team_in_charge)) {
                $user_model = User::where('id', $user->registration->team_in_charge)->first();
                $user->id = isset($user_model->id) ? $user_model->id : $user->id;
            }
        }

        $sub_buyer_emails = Registration::where('is_sub_account', 1)->where('team_in_charge', $user->id)->pluck('email');
        $sub_buyer_ids = User::whereIn('email', $sub_buyer_emails)->pluck('id');
        $UserId[] = $user->id;

        $total_purchased = Backlink::selectRaw('SUM(prices) as total_purchased')
            ->where('status', '!=', 'Canceled')
            ->where('status', '!=', 'To Be Validated')
            ->where(function ($query) use ($sub_buyer_ids, $UserId) {
                if (count($sub_buyer_ids) > 0) {
                    return $query->whereIn('user_id', array_merge($sub_buyer_ids->toArray(), $UserId));
                } else {
                    return $query->whereIn('user_id', $UserId);
                }
            })
            ->get();

        // $total_purchased = Backlink::selectRaw('SUM(prices) as total_purchased')
        //     ->where('user_id', $user->id)
        //     ->when(count($sub_buyer_ids) > 0, function($query) use ($sub_buyer_ids){
        //         return $query->orWhereIn('user_id', $sub_buyer_ids);
        //     })
        //     ->get();

        // check if buyer deposited in promo period date
        $date1 = date('2022-11-30');
        $date2 = date('2023-01-06');

        $deposited = WalletTransaction::where('user_id', $user->id)
            ->whereIn('admin_confirmation', ['Paid'])
            ->whereBetween('date', [$date1, $date2])
            ->orderBy('created_at', 'desc')
            ->first();

        $wallet_transaction = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
            ->where('user_id', $user->id)
            ->whereIn('admin_confirmation', ['Paid'])
            ->get();

        $wallet_transaction_refunded = WalletTransaction::selectRaw('SUM(amount_usd) as amount_usd')
            ->where('user_id', $user->id)
            ->whereIn('admin_confirmation', ['Refunded'])
            ->get();

        if (isset($wallet_transaction[0]['amount_usd'])) {
            if (isset($total_purchased[0]['total_purchased'])) {
                $credit = floatval($wallet_transaction[0]['amount_usd']) - floatval($total_purchased[0]['total_purchased']);
            } else {
                $credit = floatval($wallet_transaction[0]['amount_usd']);
            }
        }

        if( isset($wallet_transaction_refunded[0]['amount_usd']) ) {
            $credit = $credit - floatval($wallet_transaction_refunded[0]['amount_usd']);
        }
        // End of Getting credit left

        if (isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All') {
            return response()->json([
                'data' => $result,
                'total' => $result->count(),
                'credit' => round($credit),
                'deposited' => isset($deposited->amount_usd) ? intval($deposited->amount_usd):0,
            ], 200);
        } else {
            $custom_credit = collect(['credit' => round($credit), 'deposited' => isset($deposited->amount_usd) ? intval($deposited->amount_usd):0]);
            $result = $custom_credit->merge($result);

            return $result;
        }
    }

    /**
     * Buy function
     *
     * @param Request $request
     * @param NotificationInterface $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, NotificationInterface $notification)
    {
        if (Gate::denies('create-buyer-list-backlinks-to-buy')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $status = 'Pending';
        $publisher = Publisher::find($request->publisher_id ? $request->publisher_id : $request->id);

        $user = User::where('id', auth()->user()->id)->first();

        if ($user->credit_auth != 'Yes' && $request->credit_left < $request->prices) {
            return response()->json([
                'message' => 'Insufficient Credits',
                'errors' => [
                    'insufficent_credits'
                ]
            ], 422);
        }

        if ($user->registration) {
            if ($user->registration->is_sub_account == 1) {
                if ($user->registration->can_validate_backlink == 0) {
                    $status = 'To Be Validated';
                }
            }
        }

        $this->updateStatus('Purchased', $publisher->id);

        $url_advertiser = $request->url_advertiser;

        // remove http
        $url = remove_http($url_advertiser);

        // remove space
        $url = trim($url_advertiser, " ");

        // remove /
        $url = rtrim($url_advertiser, "/");

        if ($request->status && $request->status == 'To Be Validated') {
            $backlink = Backlink::find($request->id);

            $backlink->update([
                'status' => 'Pending'
            ]);

            $status = 'Pending';
        } else {
            $backlink = Backlink::create([
                'prices' => $request->prices,
                'price' => $request->seller_price,
                'idea_for_title' => $request->idea_for_title,
                'url_advertiser' => $url_advertiser,
                'anchor_text' => $request->anchor_text,
                'link' => $request->link,
                'publisher_id' => $publisher->id,
                'user_id' => $user->id,
                'status' => $status,
                // default status when buys a URL
                'date_process' => date('Y-m-d'),
                'ext_domain_id' => 0,
                'int_domain_id' => 0,
                'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
            ]);
        }

        // add survey code

        if ($user->isOurs === 1 && $user->registration->survey_code === null) {
            $user->registration->update([
                'survey_code' => md5(uniqid(rand(), true))
            ]);
        }

        event(new BuyEvent($backlink, $user));

        if ($status === 'Pending') {
            event(new SellerReceivesOrderEvent($backlink, $publisher->user));
        }

        event(new BacklinkStatusChangedEvent($backlink, $backlink->publisher->user));


        if ($status === 'Pending') {
            // seller confirmation email
            $seller_account = null;

            if ($backlink->publisher) {
                $seller_account = $backlink->publisher->user ?: null;
            }

            if ($seller_account) {

                if ($seller_account->registration->survey_code === null) {
                    $seller_account->registration->update([
                        'survey_code' => md5(uniqid(rand(), true))
                    ]);
                }

                event(new SellerConfirmationEvent($backlink, $seller_account));
            }
        }

        // if (isset($backlink->publisher->inc_article) && strtolower($backlink->publisher->inc_article) == "no") {
        //     Article::create([
        //         'id_backlink' => $backlink->id,
        //         'id_language' => $backlink->publisher->language_id,
        //     ]);
        //     $users = User::where('status', 'active')->where('role_id', 4)->get();
        //     foreach ($users as $user) {
        //        event(new NotificationEvent("New Article to be write today!", $user->id));
        //     }
        // }

        return response()->json(['success' => true], 200);
    }

    public function validateOrder (Request $request)
    {
        if (isset($request->ids)) {
            foreach ($request->ids as $id) {
                // get backlink
                $backlink = Backlink::find($id);

                if ($backlink->user) {
                    if ($backlink->user->credit_auth != 'Yes' && $request->credit < $backlink->prices) {
                        return response()->json([
                            'message' => 'Insufficient Credits',
                            'errors' => [
                                'insufficient_credits'
                            ]
                        ], 422);
                    } else {
                        $backlink->update([
                            'status' => 'Pending'
                        ]);

                        // notify seller
                        event(new SellerReceivesOrderEvent($backlink, $backlink->publisher->user));

                        // notify for status change
                        event(new BacklinkStatusChangedEvent($backlink, $backlink->publisher->user));

                        // seller confirmation email
                        $seller_account = null;

                        if ($backlink->publisher) {
                            $seller_account = $backlink->publisher->user ?: null;
                        }

                        if ($seller_account) {

                            if ($seller_account->registration->survey_code === null) {
                                $seller_account->registration->update([
                                    'survey_code' => md5(uniqid(rand(), true))
                                ]);
                            }

                            event(new SellerConfirmationEvent($backlink, $seller_account));
                        }
                    }
                }
            }
        }
    }

    public function updateDislike(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                $publisher = Publisher::find($id);
                $this->updateStatus('Not interested', $publisher->id);
            }
        } else {
            $publisher = Publisher::find($request->id);
            $this->updateStatus('Not interested', $publisher->id);
        }

        return response()->json(['success' => true], 200);
    }

    public function updateLike(Request $request)
    {
        $user = auth()->user();

        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                $publisher = Publisher::find($id);
                $this->updateStatus('Interested', $publisher->id);

//                Backlink::create([
//                    'prices' => $request->prices,
//                    'price' => $request->seller_price,
//                    'url_advertiser' => $request->url_advertiser,
//                    'anchor_text' => $request->anchor_text,
//                    'link' => $request->link,
//                    'publisher_id' => $publisher->id,
//                    'user_id' => $user->id,
//                    'status' => 'To Be Validated',
//                    'date_process' => date('Y-m-d'),
//                    'ext_domain_id' => 0,
//                    'int_domain_id' => 0,
//                    'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
//                ]);
            }
        } else {
            $publisher = Publisher::find($request->id);
            $this->updateStatus('Interested', $publisher->id);

//            Backlink::create([
//                'prices' => $request->prices,
//                'price' => $request->seller_price,
//                'url_advertiser' => $request->url_advertiser,
//                'anchor_text' => $request->anchor_text,
//                'link' => $request->link,
//                'publisher_id' => $publisher->id,
//                'user_id' => $user->id,
//                'status' => 'To Be Validated',
//                'date_process' => date('Y-m-d'),
//                'ext_domain_id' => 0,
//                'int_domain_id' => 0,
//                'is_https' => $this->httpClient->getProtocol($request->url_advertiser) == 'https' ? 'yes' : 'no'
//            ]);
        }

        return response()->json(['success' => true], 200);
    }

    public function updateInterestedNew (Request $request) {
        $user = auth()->user();

        $buyer_purchased = BuyerPurchased::where([
                        'user_id_buyer' => $user->id,
                        'publisher_id' => $request->id
                    ]);

        if($buyer_purchased->count() > 0) {
            $buyer_purchased->update([
                'status' => 'Interested'
            ]);
        } else {
            BuyerPurchased::create([
                'user_id_buyer' => $user->id,
                'publisher_id' => $request->id,
                'status' => 'Interested'
            ]);
        }

        $interested = BacklinksInterested::where('publisher_id', $request->id)
            ->where('user_id', $user->id)
            ->first();

        if ($interested) {
            $interested->update([
                'link' => $request->link,
                'anchor_text' => $request->anchor_text,
                'url_advertiser' => $request->url_advertiser,
            ]);
        } else {
            $new = new BacklinksInterested();

            $new->user_id = Auth::user()->id;
            $new->publisher_id = $request->id;
            $new->link = $request->link;
            $new->anchor_text = $request->anchor_text;
            $new->url_advertiser = isset($request->url_advertiser) ? $request->url_advertiser : null;

            $new->save();
        }

        return response()->json(['success' => true], 200);
    }

    public function updateUninterestedNew (Request $request) {
        $user = auth()->user();

        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                $buyerPurchasedIds = BuyerPurchased::where([
                    'user_id_buyer' => $user->id,
                    'status' => 'Interested',
                    'publisher_id' => $id
                ])->get()->pluck('id');

                BuyerPurchased::destroy($buyerPurchasedIds);
            }
        } else {
            $buyerPurchasedIds = BuyerPurchased::where([
                'user_id_buyer' => $user->id,
                'status' => 'Interested',
                'publisher_id' => $request->id
            ])->get()->pluck('id');

            BuyerPurchased::destroy($buyerPurchasedIds);
        }

        return response()->json(['success' => true], 200);
    }

    public function UpdateUninterested(Request $request)
    {
        $backlink = Backlink::find($request->backlink_id);

        $buyerPurchasedIds = BuyerPurchased::where([
            'user_id_buyer' => $backlink->user_id,
            'publisher_id' => $request->publisher_id
        ])->get()->pluck('id');

        Backlink::destroy($backlink->id);
        BuyerPurchased::destroy($buyerPurchasedIds);

        return 'OK';
    }

    public function UpdateMultipleUninterested(Request $request) {
        $ids = $request->ids;
        $backlinks = Backlink::whereIn('id', $ids);
        $buyerPurchasedIds = BuyerPurchased::whereIn('user_id_buyer', $backlinks->pluck('user_id'))
                ->whereIn('publisher_id', $backlinks->pluck('publisher_id'))
                ->get()->pluck('id');


        $backlinks->delete();
        BuyerPurchased::destroy($buyerPurchasedIds);

        return 'OK';
    }

    public function checkCreditAuth()
    {
        $data = Auth::user()->credit_auth;

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    private function checkCombination($val) {
        $combination = [
            'AAA' => 'A',
            'AAB' => 'A',
            'AAC' => 'B',
            'AAD' => 'C',
            'AAE' => 'B',

            'BBA' => 'A',
            'BBB' => 'B',
            'BBC' => 'B',
            'BBD' => 'C',
            'BBE' => 'D',

            'CCA' => 'B',
            'CCB' => 'C',
            'CCC' => 'C',
            'CCD' => 'D',
            'CCE' => 'E',

            'DDA' => 'D',
            'DDB' => 'D',
            'DDC' => 'E',
            'DDD' => 'E',
            'DDE' => 'E',

            'EEA' => 'E',
            'EEB' => 'E',
            'EEC' => 'E',
            'EED' => 'E',
            'EEE' => 'E',

            'BAA' => 'A',
            'BAB' => 'A',
            'BAC' => 'B',
            'BAD' => 'B',

            'BCC' => 'B',
            'BCD' => 'C',
            'BCE' => 'E',

            'BDC' => 'C',
            'BDD' => 'D',
            'BDE' => 'E',
            'BEE' => 'E',

            'CAC' => 'D',
            'CAD' => 'E',

            'CBC' => 'D',
            'CBB' => 'C',
            'CBD' => 'E',

            'CDD' => 'D',
            'CEE' => 'E',

            'DAD' => 'D',
            'DBC' => 'D',
            'DCA' => 'D',
            'DCB' => 'D',
            'DCC' => 'E'
        ];

        if(array_key_exists($val , $combination)) {
            return $combination[$val];
        } else {
            return 'E';
        }
    }

    private function getCodeCombination( $a , $b, $type )
    {

        switch ( $type ) {

            case "value1":
                $val = 'E';
                $comb1 = ''; // UR
                $comb2 = ''; // UR
                $comb3 = ''; // Condition 2

                // UR
                if($a >= 50 && $a <= 100) {
                    $comb1 = 'A';
                } else if($a >= 26 && $a <= 49) {
                    $comb1 = 'B';
                } else if($a >= 10 && $a <= 25) {
                    $comb1 = 'C';
                } else if($a >= 6 && $a <= 9) {
                    $comb1 = 'D';
                } else if($a >= 0 && $a <= 5) {
                    $comb1 = 'E';
                }

                // DR
                if($b >= 50 && $b <= 100) {
                    $comb2 = 'A';
                } else if($b >= 26 && $b <= 49) {
                    $comb2 = 'B';
                } else if($b >= 10 && $b <= 25) {
                    $comb2 = 'C';
                } else if($b >= 6 && $b <= 9) {
                    $comb2 = 'D';
                } else if($b == 0 && $b <= 5) {
                    $comb2 = 'E';
                }

                $score = $b - $a;
                //condition 2
                if($score >= 0 && $score <= 7) {
                    $comb3 = 'A';
                } else if($score >= 8 && $score <= 15) {
                    $comb3 = 'B';
                } else if( ($score >= 16 && $score <= 21) || ($score >= -5 && $score <= -1) ) {
                    $comb3 = 'C';
                } else if( ($score >= 22 ) || ($score >= -10 && $score <= -6) ) {
                    $comb3 = 'D';
                } else if ($score <= -11){
                    $comb3 = 'E';
                }

                $val = $this->checkCombination($comb1.$comb2.$comb3);

                return $val;

            case "value2":

                $val = '';

                if( $a == 0 || $b == 0){
                    $score = 0;
                    $val = 'A';
                }else{
                    $score = number_format( floatVal($a / $b) , 0, '.', '');
                }

                if( $score >= 0.99 && $score <= 5){  $val = 'A'; }
                else if( $score >= 6 && $score <= 16){ $val = 'B'; }
                else if( $score >= 17 && $score <= 22){ $val = 'C'; }
                else if( $score >= 23 && $score <= 60){ $val = 'D'; }
                else if( $score >= 61 ){ $val = 'E'; }
                else if( $score < 0.99 ){ $val = 'E'; }

                return $val;

            case "value3":

                $val = 'E';

                if( $a >= 500){ $val = 'A'; }
                else if( $a >= 200 && $a < 500){ $val = 'B'; }
                else if( $a >= 100 && $a < 200){ $val = 'C'; }
                else if( $a >= 50 && $a < 100){ $val = 'D'; }
                else if( $a < 50 ){ $val = 'E'; }

                return $val;

            case "value4":

                $val = 'E';

                if( $a >= 10000){ $val = 'A'; }
                else if( $a >= 5000 && $a < 10000){ $val = 'B'; }
                else if( $a >= 1000 && $a < 5000){ $val = 'C'; }
                else if( $a >= 500 && $a < 1000){ $val = 'D'; }
                else if( $a < 500 ){ $val = 'E'; }

                return $val;

            default:

                return '';

        }

    }

    protected function percentage($percent, $total) {
        return number_format(($percent / 100) * $total, 2);
    }

    protected function getFormulaData()
    {
        return DB::table('formula')->first();
    }
}
