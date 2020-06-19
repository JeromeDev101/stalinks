<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Backlink;
use Illuminate\Support\Facades\DB;

class NewBacklinkController extends Controller
{
    public function getList(Request $request){
        $list = Backlink::get();

        $list = DB::table('backlinks')
            ->select('backlinks.*','users.name', 'users.isOurs')
            ->leftJoin('users', 'backlinks.user_id', '=', 'users.id');

        return [
            'data' => $list->get()
        ];
    }
}
