<?php

namespace App\Http\Controllers;

use App\Models\AffiliateCode;
use Illuminate\Http\Request;

class AffiliateCodeController extends Controller
{
    public function index () {
        return AffiliateCode::where('user_id', auth()->user()->id)
            ->paginate(10);
    }

    public function store (Request $request) {
        $code = new AffiliateCode();
        $code->user_id = $request->user_id;
        $code->name = $request->name;
        $code->affiliate_code = $request->code;

        $code->save();
    }
}
