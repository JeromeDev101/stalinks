<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Publisher;

class PurchaseController extends Controller
{
    /**
     * Get list of Backlinks with purchase
     *
     * @return list
     */
    public function getList(Request $request) {
        $user = Auth::user();
        $list = Backlink::with(['publisher' => function($query){
            $query->with('user:id,name');
        }, 'user'])
                    ->where('status', 'Live')
                    ->orderBy('created_at', 'desc');

        if( !$user->isAdmin() ){
            $list->where('user_id', $user->id);
        }

        return [
            'data' => $list->get()
        ];
    }
}
