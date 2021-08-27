<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToolRequest;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request) {
        $tools = Tool::select('*');

        if ($request->has('name')) {
            $tools->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('username')) {
            $tools->where('username', 'like', '%' . $request->username . '%');
        }

        if( $request->has('paginate') && $request->paginate == 'All' ){
            $tools = $tools->get();

            return response()->json([
                'data' => $tools,
                'total' => $tools->count(),
            ],200);
        }else{
            $tools = $tools->paginate($request->paginate);

            return response()->json($tools);
        }
    }

    public function store(ToolRequest $request) {
        $tool = new Tool();

        $tool->fill($request->all());

        $tool->save();

        return response()->json(['success' => true], 200);
    }

    public function update(ToolRequest $request)
    {
        $inputs = $request->only('name', 'username', 'password');

        $tool = Tool::find($request->id);

        if (!$tool) {
            return response()->json(['success' => false], '422');
        }else{
            $tool->update($inputs);
            return response()->json(['success' => true], '200');
        }
    }

    public function destroy($id)
    {
        Tool::find($id)->delete();

        return response()->json(['success' => true]);
    }
}
