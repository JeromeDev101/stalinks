<?php

namespace App\Http\Controllers;

use App\Models\Continent;
use Illuminate\Http\Request;

class ContinentController extends Controller
{
    public function getListContinent() {
        $country = Continent::orderBy('name', 'asc');
        return response()->json([
            'data' => $country->get(),
            'count' => $country->count(),
        ],200);
    }
}
