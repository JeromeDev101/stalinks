<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Publisher;

class BuyController extends Controller
{
    public function getList(){
        $list = DB::table('publisher')
                    ->select('publisher.*','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS language')
                    ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                    ->leftJoin('registration', 'users.email', '=', 'registration.email')
                    ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                    ->get();


        return [
            'data' => $list
        ];
    }

    public function update(Request $request) {
        $input = $request->all();
        $publisher = Publisher::findOrFail($request->id);
        $publisher->update($input);

        return response()->json(['success'=> true], 200);
    }
}
