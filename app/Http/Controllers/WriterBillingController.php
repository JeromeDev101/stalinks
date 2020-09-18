<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use App\Models\BillingWriter;

class WriterBillingController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $list = Article::select('article.*', 'billing_writer.proof_doc_path')
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->leftJoin('price', 'article.id_writer_price', '=', 'price.id')
                        ->leftJoin('users', 'article.id_writer', '=', 'users.id')
                        ->leftJoin('billing_writer', 'article.id', '=', 'billing_writer.id_article')
                        ->with('backlinks:id,title,status')
                        ->with('country:id,name')
                        ->where('article.status_writer', 'Done')
                        ->where('users.role_id', 4)
                        ->where('users.isOurs', 1)
                        ->with('user:id,name,username')
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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $ids = json_decode($request->ids);

        $image = $request->file;
        $new_name = time() . '-billing-writer.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/billing'), $new_name);
        $article_ids = [];
        foreach( $ids as $data ){
            $article_id = $data->id;
            $user_id_writer = $data->id_writer;
            $article_ids[] = $article_id;

            $article = Article::FindOrFail($article_id);
            $article->update(['payment_status' => 'Paid']);

            BillingWriter::create([
                'id_article' => $article_id,
                'id_user' => $user_id_writer,
                'price' => $request->price,
                'id_payment_via' => intVal($request->id_payment_type),
                'date_billing' => date('Y-m-d'),
                'proof_doc_path' => '/images/billing/'.$new_name,
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    public function getWriterInfo(Request $request) {
        $writer_id = [];
        $result = [
            'data' => [],
            'success' => false
        ];
        foreach($request->ids as $data){
            if( isset($data['id_writer']) ){
                $writer_id[] = $data['id_writer'];
            }
        }

        $check = array_unique($writer_id);

        if( count($check) > 1){
            return $result;
        }

        if( isset($check[0]) ){
            $writer_info = User::where('id', $check[0])->with(['paymentType', 'registration'])->get();

            $result = [
                'data' => $writer_info,
                'success' => true
            ];
        }

        return $result;
    }
}
