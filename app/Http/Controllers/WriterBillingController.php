<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class WriterBillingController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $list = Article::select('article.*')
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->leftJoin('price', 'article.id_writer_price', '=', 'price.id')
                        ->with('price')
                        ->with('backlinks:id,title,status')
                        ->with('country:id,name')
                        ->where('status_writer', 'Done')
                        ->with('user:id,name')
                        ->orderBy('id', 'desc');

        if( isset($filter['search_backlink'] ) && $filter['search_backlink'] ){
            $list->where('article.id_backlink', $filter['search_backlink']);
        }

        if( isset($filter['writer'] ) && $filter['writer'] ){
            $list->where('article.id_writer', $filter['writer']);
        }

        return [
            'data' => $list->get(),
        ];
    }

    public function payBilling(Request $request) {
        $request->validate([
            'payment_type' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        return response()->json(['success' => true], 200);
    }
}
