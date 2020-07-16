<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

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
                        ->with('backlinks:id,title,anchor_text,status')
                        // ->whereNotNull('date_complete')
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

        $list = Article::with('country:id,name')
                        ->with('backlinks:id,title,anchor_text,status')
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
            $list = $list->where('id_writer', $user->id);
        }

        return [
            'data' => $list->get()
        ];
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
        dd($request->all());

        $article = Article::find($request->content['id']);
        $article->update([
            'date_start' => $request->data == null ? null:date('Y-m-d'),
            'content' => $request->data,
            'date_complete' => $request->content['status'] == 'Content sent' ? date('Y-m-d'):null,
        ]);

        return response()->json(['success'=>true], 200);
    }
}
