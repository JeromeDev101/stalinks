<?php

namespace App\Http\Controllers;

use App\Events\ArticleDoneEvent;
use App\Events\NewArticleEvent;
use App\Repositories\Contracts\NotificationInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\Publisher;
use App\Models\Price;
use Illuminate\Support\Facades\Gate;


class ArticlesController extends Controller
{
    public function getList(){
        $list = Backlink::select('backlinks.*')
            ->leftJoin('publisher', function($join){
                $join->on('backlinks.publisher_id', '=', 'publisher.id');
            })
            ->where('publisher.inc_article', 'Yes')
            ->whereNotIn('backlinks.status', ['Issue', 'Canceled', 'Pending', 'Live'])
            ->with('publisher:id,url,inc_article,language_id')
            ->with('user:id,name');

        return [
            'total' => $list->count(),
            'data' => $list->get(),
        ];
    }

    public function getArticleListAdmin(Request $request) {
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:15;
        $user = Auth::user();
        $registration = Registration::where('email', $user->email)->first();
        $columns = [
            'article.*',
            'users.isOurs',
            'registration.rate_type',
            'registration.writer_price',
        ];

        $isExtWriter = (Auth::user()->role_id == 4 && Auth::user()->isOurs == 1) ? true:false;

        $list = Article::select($columns)
                        ->leftjoin('users', 'article.id_writer', '=', 'users.id')
                        ->leftjoin('registration', 'users.email', '=' , 'registration.email')
                        ->with('language:id,name')
                        ->with('price')
                        ->with(['backlink' => function($q){
                            $q->with(['publisher' => function($sub){
                                $sub->with('user:id,name')->with('country:id,name');
                            }])
                            ->with('user:id,name');
                        }])
                        ->with('user:id,name,username')
                        ->where('status_writer', 'Done')
                        ->orderBy('id', 'desc');

        if( isset($filter['date']) && $filter['date'] ){
            if( $filter['date_type'] == 'Started'){
                $list->where('date_start', $filter['date']);
            }

            if( $filter['date_type'] == 'Completed'){
                $list->where('date_complete', $filter['date']);
            }
        }

        if( $user->isOurs == 1 && isset($registration->type) && $registration->type == 'Buyer' ){
            // if( $user->role_id == 6 ){
                $backlinks_ids = $this->getBacklinksForBuyer();

                $list->whereIn('article.id_backlink', $backlinks_ids);
        }

        if( isset($filter['writer']) && $filter['writer'] != ""){
            $list->where('id_writer', $filter['writer']);
        }

        if( isset($filter['search_backlink']) && $filter['search_backlink'] != ""){
            $list->where('id_backlink', $filter['search_backlink']);
        }

        if( isset($filter['search_article']) && $filter['search_article'] != ""){
            $list->where('article.id', $filter['search_article']);
        }

        if( isset($filter['language_id']) && $filter['language_id'] != ""){
            $list->where('id_language', $filter['language_id']);
        }

        return $list->paginate($paginate);
    }

    public function getArticleList(Request $request) {
        $filter = $request->all();
        $paginate = isset($filter['paginate']) && !empty($filter['paginate']) ? $filter['paginate']:50;
        $user = Auth::user();
        $registration = Registration::where('email', $user->email)->first();

        $columns = [
            'article.*',
            'publisher.topic',
            'publisher.casino_sites',
            'users.username as writer',
            'backlinks.status as backlink_status'
        ];

        $isExtWriter = Auth::user()->role_id == 4 && Auth::user()->isOurs == 1;

        $list = Article::select($columns)
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                        ->leftJoin('users', 'article.id_writer', '=', 'users.id')
                        ->with('price')
                        ->with('language:id,name')
                        ->with(['backlink' => function($q){
                            $q->with(['publisher' => function($sub){
                                $sub->with('user:id,name')->with('country:id,name');
                            }])
                            ->with('user:id,name');
                        }])
                        ->when($isExtWriter, function($query) use ($user) {
                            return $query->where(function($sub) use ($user) {
                                $sub->where(function ($sub2) {
                                    $sub2->whereNull('id_writer')
                                        ->whereNull('status_writer')
                                        ->where('backlinks.status', '!=', 'Canceled');
                                })->orWhere('id_writer', $user->id);
                            })->whereIn('article.id_language', json_decode($user->registration->language_id));
                        })
                        ->where('backlinks.status' ,'!=', 'Pending')
                        // ->where('backlinks.status' ,'!=', 'Issue')
                        ->orderBy('id', 'desc');

        if( isset($filter['search_backlink']) && $filter['search_backlink'] ){
            $list->where('article.id_backlink', $filter['search_backlink']);
        }

        if( isset($filter['search_article']) && $filter['search_article'] ){
            $list->where('article.id', $filter['search_article']);
        }

        if( isset($filter['writer']) && $filter['writer'] != ""){
            $list->where('id_writer', $filter['writer']);
        }

        if( isset($filter['casino_sites']) && $filter['casino_sites'] != ""){
            $list->where('publisher.casino_sites', $filter['casino_sites']);
        }

        if( isset($filter['topic']) && $filter['topic'] != ""){
            $list->whereIn('publisher.topic', $filter['topic']);
        }

        if( isset($filter['language_id']) && $filter['language_id'] != ""){
            $list->where('article.id_language', $filter['language_id']);
        }

        if( isset($filter['status']) && $filter['status'] != ""){
            if( $filter['status'] == 'Queue' ){
                $list->whereNull('article.status_writer');
                $list->whereNotIn('backlinks.status', ['Issue', 'Canceled']);
            }else if( $filter['status'] == 'Canceled' || $filter['status'] == 'Issue' ){
                $list->whereHas('backlink', function($query) use ($filter) {
                    return $query->where('status', $filter['status']);
                })->whereNull('article.status_writer');

                if ($filter['status'] === 'Issue') {
                    $list->orWhere('article.status_writer', 'Issue');
                }
            }else{
                $list->where('article.status_writer', $filter['status']);
            }
        }

        if( $user->isOurs == 1 && isset($registration->type) && $registration->type == 'Seller' ){
        // if( $user->role_id == 6 ){
            $backlinks_ids = $this->getBacklinksForSeller();

            $list->whereIn('article.id_backlink', $backlinks_ids);
        }

        if( $user->isOurs == 1 && isset($registration->type) && $registration->type == 'Buyer' ){
            // if( $user->role_id == 6 ){
                $backlinks_ids = $this->getBacklinksForBuyer();

                $list->whereIn('article.id_backlink', $backlinks_ids);
        }


        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            $list = $list->paginate(9999999);
        }else{
            $paginate = intval($paginate);
            $list = $list->paginate($paginate);
        }

//        $statusSummary = $this->backlinkSeller();
        $statusSummary = $this->getArticleStatusSummaryTotals();

        return response()->json(['data' => $list, 'summary'=> $statusSummary],200);

    }

    private function backlinkSeller() {
        $user_id = Auth::user()->id;
        $columns = [
            'users.username',
            DB::raw('SUM(CASE WHEN backlinks.status = "Processing" THEN 1 ELSE 0 END) AS num_processing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Writing" THEN 1 ELSE 0 END) AS writing'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Content Done" THEN 1 ELSE 0 END) AS num_done'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Canceled" THEN 1 ELSE 0 END) AS num_canceled'),
            DB::raw('SUM(CASE WHEN backlinks.status = "Issue" THEN 1 ELSE 0 END) AS num_issue'),
        ];

        $list = Backlink::select($columns)
                    ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email');

        if( Auth::user()->role_id == 4 && Auth::user()->isOurs == 1 ) {
            $list = $list->whereHas('article', function($query) use ($user_id) {
                return $query->where('id_writer', $user_id);
            });
        }

        return $list->groupBy('publisher.user_id', 'users.username')
                    ->orderBy('users.username', 'asc')
                    ->get();
    }

    private function getArticleStatusSummaryTotals () {
        $user = Auth::user();
        $isExtWriter = Auth::user()->role_id == 4 && Auth::user()->isOurs == 1;

        $columns = [
            DB::raw('SUM(CASE WHEN article.status_writer is null and backlinks.status not in ("Issue", "Canceled") THEN 1 ELSE 0 END) AS total_queue'),
            DB::raw('SUM(CASE WHEN article.status_writer = "In Writing" THEN 1 ELSE 0 END) AS total_in_writing'),
            DB::raw('SUM(CASE WHEN article.status_writer = "Done" THEN 1 ELSE 0 END) AS total_done'),
            DB::raw('SUM(CASE WHEN exists (select * from backlinks where article.id_backlink = backlinks.id and status = "Canceled" and backlinks.deleted_at is null) AND article.status_writer is null THEN 1 ELSE 0 END) AS total_cancelled'),
            DB::raw('SUM(CASE WHEN exists (select * from backlinks where article.id_backlink = backlinks.id and status = "Issue" and backlinks.deleted_at is null) AND article.status_writer is null OR article.status_writer = "Issue" THEN 1 ELSE 0 END) AS total_issue'),
        ];

        return Article::select($columns)
            ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
            ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
            ->leftJoin('users', 'article.id_writer', '=', 'users.id')
            ->where('backlinks.status' ,'!=', 'Pending')
            ->when($isExtWriter, function($query) use ($user) {
                return $query->where(function($sub) use ($user) {
                    $sub->where(function ($sub2) {
                        $sub2->whereNull('id_writer')
                            ->whereNull('status_writer')
                            ->where('backlinks.status', '!=', 'Canceled');
                    })->orWhere('id_writer', $user->id);
                })->whereIn('article.id_language', json_decode($user->registration->language_id));
            })->first();
    }

    private function getBacklinksForSeller() {
        $user = Auth::user();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();
        $backlinks_ids = Backlink::whereIn('publisher_id', $publisher_ids)->pluck('id')->toArray();

        return $backlinks_ids;
    }

    private function getBacklinksForBuyer() {
        $user = Auth::user();
        $subBuyer = Registration::select('users.id AS id')->join('users', 'registration.email', 'users.email')->where('registration.team_in_charge', $user->id)->get()->pluck('id');
        $backlinks_ids = Backlink::where('user_id', $user->id)->orWhereIn('user_id', $subBuyer)->pluck('id')->toArray();

        return $backlinks_ids;
    }

    public function getWriterList() {
        $writers = User::select('id', 'name', 'username')
            ->where('role_id', 4)
            ->where('isOurs', 1)
            ->where('status', 'active')
            ->orderBy('username', 'asc')
            ->get();

        return [
            'total' => $writers->count(),
            'data' => $writers,
        ];
    }

    public function getValidExternalWriters () {
        $columns = [
            'users.id',
            'users.name',
            'users.username',
            'users.isOurs',
            'registration.language_id',
            'registration.is_exam_passed'
        ];

        return User::select($columns)
            ->where('role_id', 4)
            ->leftJoin('registration', 'users.email', '=', 'registration.email')
            ->where('users.isOurs', 1)
            ->where('users.status', 'active')
            ->where('registration.account_validation', 'valid')
            ->where('registration.is_exam_passed', 1)
            ->get();
    }

    public function store(Request $request, NotificationInterface $notification) {
        if (Gate::denies('create-article-article')) {
            abort(422, 'Unauthorized Access');
        }

        $request->validate([
            'backlink' => 'required',
            'writer' => 'required',
        ]);

        $article = Article::create([
            'id_backlink' => $request->backlink,
            'id_writer' => $request->writer,
            'id_language' => $request->language_id,
            'date_start' => date('Y-m-d'),
        ]);

        event(new NewArticleEvent($article, $article->user));

//        $notification->create([
//            'user_id' => $request->writer,
//            'notification' => 'Hello writers there is a new articles to write for ' . $article->language->name . '  for '. $article->backlinks->url_advertiser .' on '. date('Y-m-d') .' follow up with backlink ID ' . $request->backlink
//        ]);
//
//        broadcast(New NewArticleEvent($request->writer));

        return response()->json(['success' => true], 200);
    }

    public function updateContent(Request $request, NotificationInterface $notification){
        if (Gate::denies('update-article-article')) {
            abort(422, 'Unauthorized Access');
        }

        $user_id = Auth::user()->id;
        $article = Article::find($request->get('content')['id']);
        $price_id = $article->id_writer_price ?: null;
        $price = null;

//        if($article->price){
//            $article->price->update([
//                'price' => $request->get('content')['price']
//            ]);
//
//            $price_id = $article->price->id;
//        }else{
//            $price = Price::create([
//                'price' => $request->get('content')['price'],
//                'id_user' => $user_id,
//                'id_language' => $article->id_language,
//                'id_article' => $article->id,
//                'type' => 'writer'
//            ]);
//
//            $price_id = $price->id;
//        }

        $backlink = Backlink::find($article->id_backlink);
        if( $request->content['status'] == 'Done' ){
            if ( $backlink->status != 'Live' ) {
                $backlink->update(['status' => 'Content Done']);

                // check if writer is external

                if ($article->user->isOurs === 1) {
                    // save article price
                    if ($article->status_writer !== 'Done') {

                        // calculate price
                        if (count($article->user->languages)) {
                            // calculate price based on writer language price rate
                            $main = $article->user->languages->first(function ($value) use ($article) {
                                return $value->id === $article->id_language;
                            })->pivot->toArray();

                            if ($main) {
                                if ($article->user->registration->rate_type === 'ppa') {
                                    $price = $main['rate'];
                                } else {
                                    if ($request->content['num_words'] <= 1500) {
                                        $price = $main['rate'] * $request->content['num_words'];
                                    } else {
                                        $price = $main['rate'] * 1500;
                                    }
                                }
                            }
                        } else {
                            // calculate price based on language default
                            if ($article->user->registration->rate_type === 'ppa') {
                                $price = $article->language->ppa_rate;
                            } else {
                                if ($request->content['num_words'] <= 1500) {
                                    $price = $article->language->ppw_rate * $request->content['num_words'];
                                } else {
                                    $price = $article->language->ppw_rate * 1500;
                                }
                            }
                        }

                        if ($article->price) {
                            $article->price->update([
                                'price' => round($price, 2)
                            ]);

                            $price_id = $article->price->id;
                        } else {
                            $price = Price::create([
                                'price' => round($price, 2),
                                'id_user' => $user_id,
                                'id_language' => $article->id_language,
                                'id_article' => $article->id,
                                'type' => 'writer'
                            ]);

                            $price_id = $price->id;
                        }
                    }
                }
            }

            event(new ArticleDoneEvent($article, auth()->user()));
        }

        if( $request->content['status'] == 'In Writing' ){
            $backlink->update(['status' => 'Content In Writing']);
        }

        if (isset($request->get('content')['title'])) {
            $backlink->update(['title' => $request->get('content')['title']]);
        }

        $article->update([
            'id_writer_price' => $price_id,
//            'date_start' => $request->data == null ? null:date('Y-m-d'), // removed for accept article button
            'content' => $request->data,
            'date_complete' => $request->content['status'] == 'Done' ? date('Y-m-d'):null,
            'status_writer' => $request->content['status'],
//            'id_writer' => $user_id, // removed for accept article button
            'num_words' => $request->content['num_words'],
            'meta_description' => $request->content['meta_description'],
            'meta_keyword' => $request->content['meta_keyword'],
            'note' => $request->content['note'],
        ]);

        return response()->json(['success'=>true], 200);
    }

    public function deleteArticle(Request $request) {
        if (Gate::denies('delete-admin-article-admin-article')) {
            abort(422, 'Unauthorized Access');
        }

        $article = Article::find($request->id);
        $article->delete();

        return response()->json(['success' => true], 200);
    }

    public function acceptDeclineArticle(Request $request)
    {
        $article = Article::find($request->article_id);

        if ($request->mode === 'accept') {
            $article->update([
                'id_writer' => $request->writer_id,
                'status_writer' => 'In Writing',
                'date_start' => Carbon::now()
            ]);

            // add survey code for writer if null
            if ($article->user->isOurs === 1) {
                if ($article->user->registration->survey_code === null) {
                    $article->user->registration->update([
                        'survey_code' => md5(uniqid(rand(), true))
                    ]);
                }
            }

        } else {
            $article->update([
                'id_writer' => null,
                'status_writer' => null,
                'date_start' => null
            ]);
        }

        return response()->json(['success'=>true], 200);
    }
}
