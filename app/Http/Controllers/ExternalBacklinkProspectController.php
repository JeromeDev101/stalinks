<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BacklinkProspect;

class ExternalBacklinkProspectController extends Controller
{
    public function fetchBacklinkProspect(Request $request) {
        $data = BacklinkProspect::all();
        return response()->json(['data' => $data]);

    }
}
