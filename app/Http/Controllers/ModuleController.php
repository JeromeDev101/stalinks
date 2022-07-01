<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index (Request $request) {
        $module = Module::select('*');

        if (isset($request->group) && $request->group != null) {
            $module->where('group', $request->group);
        }

        if (isset($request->page_name) && $request->page_name != null) {
            $module->where('page', $request->page_name);
        }

        if( isset($request->paginate) && $request->paginate == 'All' ){
            $module = $module->get();

            return response()->json([
                'data' => $module,
                'total' => $module->count(),
            ],200);
        }else{
            $module = $module->paginate($request->paginate);

            return response()->json($module);
        }
    }

    public function store (ModuleRequest $request) {
        $module = new Module();

        $module->fill($request->all());

        $module->save();

        return response()->json(['success' => true], 200);
    }

    public function update (ModuleRequest $request) {
        $inputs = $request->only('group', 'page', 'description');

        $module = Module::find($request->id);

        if (!$module) {
            return response()->json(['success' => false], '422');
        }else{
            $module->update($inputs);
            return response()->json(['success' => true], '200');
        }
    }

    public function destroy ($id) {
        Module::find($id)->delete();

        return response()->json(['success' => true]);
    }

    public function getFilterValues () {
        $group = Module::select('group')->groupBy('group')->get();
        $page = Module::select('page')->groupBy('page')->get();

        return response()->json([
            'group' => $group->pluck('group')->sort()->toArray(),
            'page' => $page->pluck('page')->sort()->toArray(),
        ],200);
    }
}
