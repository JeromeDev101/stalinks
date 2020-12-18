<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExtDomain;
use App\Models\User;
use App\Models\Publisher;

class UrlProspectController extends Controller
{
    public function getList(Request $request) {
        $filter = $request->all();
        
        $column = [
            'ext_domains.*',
            'users.username as employee_username',
            'countries.name as country_name'
        ];

        $list = ExtDomain::select($column)
                        ->leftJoin('users', 'ext_domains.user_id', 'users.id')
                        ->leftJoin('countries', 'ext_domains.country_id', 'countries.id')
                        ->orderBy('ext_domains.id', 'desc')
                        ->paginate(15);

        return response()->json(['success' => true, 'data' => $list]);
    }
}
