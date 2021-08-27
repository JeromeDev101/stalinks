<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToolRequest;
use App\Models\Tool;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function index(Request $request) {
        $tools = Tool::select('*');

        if (isset($request->name) && $request->name != null) {
            $tools->where('name', 'like', '%' . $request->name . '%');
        }

        if (isset($request->url) && $request->url != null) {
            $tools->where('url', 'like', '%' . $request->url . '%');
        }

        if (isset($request->username) && $request->username != null) {
            $tools->where('username', 'like', '%' . $request->username . '%');
        }

        if (isset($request->details) && $request->details != null) {
            $tools->where('details', 'like', '%' . $request->details . '%');
        }

        if( isset($request->paginate) && $request->paginate == 'All' ){
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
        $inputs = $request->only('url', 'name', 'username', 'password', 'details');

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
