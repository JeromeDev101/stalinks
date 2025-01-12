<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $email
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = DB::table('labels')
            ->where('user_id', null)
            ->orWhere('user_id', Auth::user()->id)
            ->get();
        return response()->json(['status'=> 200,'labels'=> $labels]);
    }

    public function getLabels($email)
    {
        $user = $email !== 'all'
            ? User::select('id')->where('work_mail', $email)->first()
            : null;

        $labels = DB::table('labels')->where('user_id', null);

        if ($email !== 'all') {
            $labels = $labels->orWhere('user_id', $user->id);
        } else {
            $labels = $labels->orWhere('user_id', '!=', null);
        }

        $labels = $labels->get();

        return response()->json(['status'=> 200,'labels'=> $labels]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'color' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        DB::table('labels')->insert([
            'name'  => $request->name,
            'color' => $request->color,
            'user_id' => Auth::user()->id
        ]);
        return response()->json(['status'=> 200,'labels'=> $request->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'color' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        DB::table('labels')->where('id', $id)->update([
            'name'  => $request->name,
            'color' => $request->color
        ]);
        return response()->json(['status'=> 200,'labels'=> $request->all()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('labels')->where('id', $id)->delete();
        return response()->json(['status'=> 200,'message'=> 'Successfully Deleted!']);
    }
}
