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

    public function getArticleList(Request $request) {
        $user = Auth::user();

        $list = Article::with('country:id,name')
                        ->with('backlinks:id,title,anchor_text,status');
        
                        
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
        $article = Article::find($request->content['id']);
        $article->update([
            'content' => $request->data,
            'date_complete' => $request->content['status'] == 'Content sent' ? date('Y-m-d'):null,
        ]);

        return response()->json(['success'=>true], 200);
    }
}
