<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class WriterBillingController extends Controller
{
    public function getList(Request $request){
        $list = Article::with('backlinks:id,title,status')
                        ->with('country:id,name')
                        ->whereNotNull('date_complete')
                        ->orderBy('id', 'desc')
                        ->get();

        return [
            'data' => $list,
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
