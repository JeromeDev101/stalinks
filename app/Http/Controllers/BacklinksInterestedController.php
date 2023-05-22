<?php

namespace App\Http\Controllers;

use App\Models\BacklinksInterested;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BacklinksInterestedController extends Controller
{
    public function store (Request $request) {
        $interested = BacklinksInterested::where('publisher_id', $request->id)->first();

        if ($interested) {
            $interested->update([
                'link' => $request->link,
                'anchor_text' => $request->anchor_text,
                'url_advertiser' => $request->url_advertiser,
            ]);
        } else {
            $new = new BacklinksInterested();

            $new->user_id = Auth::user()->id;
            $new->publisher_id = $request->id;
            $new->link = $request->link;
            $new->anchor_text = $request->anchor_text;
            $new->url_advertiser = isset($request->url_advertiser) ? $request->url_advertiser : null;

            $new->save();
        }

        return response()->json(['success' => true], 200);
    }
}
