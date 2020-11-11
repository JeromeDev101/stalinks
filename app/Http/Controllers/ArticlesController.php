<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;
use App\Models\Price;

class ArticlesController extends Controller
{
    public function getList(){
        $list = Backlink::select('backlinks.*')
                        ->leftJoin('publisher', function($join){
                            $join->on('backlinks.publisher_id', '=', 'publisher.id');
                        })
                        ->where('publisher.inc_article', 'Yes')
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
        $list = Article::with('country:id,name')
                        ->with('price')
                        ->with(['backlinks' => function($q){
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

        if( isset($filter['writer']) && $filter['writer'] != ""){
            $list->where('id_writer', $filter['writer']);
        }
        
        if( isset($filter['search_backlink']) && $filter['search_backlink'] != ""){
            $list->where('id_backlink', $filter['search_backlink']);
        }

        if( isset($filter['search_article']) && $filter['search_article'] != ""){
            $list->where('id', $filter['search_article']);
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


        $list = Article::select('article.*', 'publisher.topic', 'publisher.casino_sites', 'users.username as writer')
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->leftJoin('publisher', 'backlinks.publisher_id', '=', 'publisher.id')
                        ->leftJoin('users', 'article.id_writer', '=', 'users.id')
                        ->with('price')
                        ->with('country:id,name')
                        ->with(['backlinks' => function($q){
                            $q->with(['publisher' => function($sub){
                                $sub->with('user:id,name')->with('country:id,name');
                            }])
                            ->with('user:id,name');
                        }])
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
            }else{
                $list->where('article.status_writer', $filter['status']);
            }
        }

        if( $user->isOurs == 1 && isset($registration->type) && $registration->type == 'Seller' ){
        // if( $user->role_id == 6 ){
            $backlinks_ids = $this->getBacklinksForSeller();

            $list->whereIn('article.id_backlink', $backlinks_ids);
        }

        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $list,
                'total' => $list->count(),
            ],200);
        }else{
            $paginate = intval($paginate);
            return $list->paginate($paginate);
        }
    }

    private function getBacklinksForSeller() {
        $user = Auth::user();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();
        $backlinks_ids = Backlink::whereIn('publisher_id', $publisher_ids)->pluck('id')->toArray();

        return $backlinks_ids;
    }

    public function getWriterList() {
        $writers = User::select('id', 'name', 'username')->where('role_id', 4)->where('isOurs', 0)->orderBy('username', 'asc')->get();

        return [
            'total' => $writers->count(),
            'data' => $writers,
        ];
    }

    public function store(Request $request) {
        $request->validate([
            'backlink' => 'required',
            'writer' => 'required',
        ]);

        Article::create([
            'id_backlink' => $request->backlink,
            'id_writer' => $request->writer,
            'id_language' => $request->language_id,
            'date_start' => date('Y-m-d'),
        ]);

        return response()->json(['success' => true], 200);
    }

    public function updateContent(Request $request){
        $user_id = Auth::user()->id;
        $article = Article::find($request->content['id']);
        $price_id = null;

        $price_list = Price::where('id_article', $article->id)->first();
        if( isset($price_list->id) ){
            $price_list->update([
                'price' => $request->content['price']
            ]);

            $price_id = $price_list->id;
        }else{
            $price = Price::create([
                'price' => $request->content['price'],
                'id_user' => $user_id,
                'id_language' => $article->id_language,
                'id_article' => $article->id,
                'type' => 'writer',
            ]);

            $price_id = $price->id;
        }

        $backlink = Backlink::find($article->id_backlink);
        if( $request->content['status'] == 'Done' ){
            if( $backlink->status != 'Live' ){
                $backlink->update(['status' => 'Content Done']);
            }
        }

        if( $request->content['status'] == 'In Writing' ){
            $backlink->update(['status' => 'Content In Writing']);
        }

        $article->update([
            'id_writer_price' => $price_id,
            'date_start' => $request->data == null ? null:date('Y-m-d'),
            'content' => $request->data,
            'date_complete' => $request->content['status'] == 'Done' ? date('Y-m-d'):null,
            'status_writer' => $request->content['status'],
            'id_writer' => $user_id,
        ]);

        return response()->json(['success'=>true], 200);
    }

    public function deleteArticle(Request $request) {
        $article = Article::find($request->id);
        $article->delete();

        return response()->json(['success' => true], 200);
    }
}
