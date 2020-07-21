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
        $list = Article::with('country:id,name')
                        ->with('price')
                        ->with(['backlinks' => function($q){
                            $q->with(['publisher' => function($sub){
                                $sub->with('user:id,name');
                            }])
                            ->with('user:id,name');
                        }])
                        ->orderBy('id', 'desc');
        
        if( isset($filter['search_backlink']) && $filter['search_backlink'] ){
            $list->where('id_backlink', 'like', '%'.$filter['search_backlink'].'%');
        }

        if( isset($filter['search_article']) && $filter['search_article'] ){
            $list->where('id', 'like', '%'.$filter['search_article'].'%');
        }

        if( isset($filter['language_id']) && $filter['language_id'] ){
            $list->where('id_language', $filter['language_id']);
        }

        return [
            'data' => $list->get(),
        ];
    }

    public function getArticleList(Request $request) {
        $filter = $request->all();
        $user = Auth::user();
        $registration = Registration::where('email', $user->email)->first();


        $list = Article::select('article.*')
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->with('price')
                        ->with('country:id,name')
                        ->with(['backlinks' => function($q){
                            $q->with(['publisher' => function($sub){
                                $sub->with('user:id,name');
                            }])
                            ->with('user:id,name');
                        }])
                        ->orderBy('id', 'desc');

        if( isset($filter['search_backlink']) && $filter['search_backlink'] ){
            $list->where('id_backlink', 'like', '%'.$filter['search_backlink'].'%');
        }

        if( isset($filter['search_article']) && $filter['search_article'] ){
            $list->where('id', 'like', '%'.$filter['search_article'].'%');
        }

        if( isset($filter['language_id']) && $filter['language_id'] ){
            $list->where('id_language', $filter['language_id']);
        }
                        
        if($user->isOurs == 0 && $user->role_id == 4){
            $list->where('id_writer', $user->id);
        }

        if( $user->isOurs == 1 && isset($registration->type) && $registration->type == 'Seller' ){
            $backlinks_ids = $this->getBacklinksForSeller();

            $list->whereIn('id_backlink', $backlinks_ids);
        }

        return [
            'data' => $list->get()
        ];
    }

    private function getBacklinksForSeller() {
        $user = Auth::user();
        $publisher_ids = Publisher::where('user_id', $user->id)->pluck('id')->toArray();
        $backlinks_ids = Backlink::whereIn('publisher_id', $publisher_ids)->pluck('id')->toArray();

        return $backlinks_ids;
    }

    public function getWriterList() {
        $writers = User::select('id', 'name')->where('role_id', 4)->where('isOUrs', 0)->get();

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

        $article->update([
            'id_writer_price' => $price_id,
            'date_start' => $request->data == null ? null:date('Y-m-d'),
            'content' => $request->data,
            'date_complete' => $request->content['status'] == 'Done' ? date('Y-m-d'):null,
            'status_writer' => $request->content['status'],
        ]);

        return response()->json(['success'=>true], 200);
    }
}
