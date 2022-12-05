<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use function GuzzleHttp\Psr7\uri_for;

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
            $module = $module
                ->with('permissions:id,module_id,name,display_name')
                ->get();

            return response()->json([
                'data' => $module,
                'total' => $module->count(),
            ],200);
        }else{
            $module = $module
                ->with('permissions:id,module_id,name,display_name')
                ->paginate($request->paginate);

            return response()->json($module);
        }
    }

    public function store (ModuleRequest $request) {
        if (Gate::denies('create-management-module')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $module = new Module();

        $module->fill($request->except('permissions'));

        $module->save();

        // add module permissions
        if (isset($request->permissions)) {
            if (is_array($request->permissions)) {
                $add = [];

                foreach ($request->permissions as $permission) {
                    $add[] = new Permission([
                        'module_id' => $module->id,
                        'name' => $permission['name'],
                        'display_name' => $permission['display_name']
                    ]);
                }

                $module->permissions()->saveMany($add);
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function update (ModuleRequest $request) {
        if (Gate::denies('update-management-module')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $inputs = $request->only('group', 'page', 'description');

        $module = Module::find($request->id);

        if (!$module) {
            return response()->json(['success' => false], '422');
        }else{
            $module->update($inputs);

            // update module permissions
            $old = $module->permissions->pluck('name')->toArray();
            $new = collect($request->permissions)->pluck('name')->toArray();

            $updateData = $this->getUpdatePermissionsData($old, $new);

            // create new permissions
            if (count($updateData['create'])) {
                $add = [];

                foreach ($updateData['create'] as $create) {
                    $add[] = new Permission([
                        'module_id' => $module->id,
                        'name' => $create,
                        'display_name' => ucwords(str_replace("-"," ", $create))
                    ]);
                }

                $module->permissions()->saveMany($add);
            }

            // delete removed permissions
            if (count($updateData['delete'])) {
                Permission::where('module_id', $module->id)->whereIn('name', $updateData['delete'])->delete();
            }

            return response()->json(['success' => true], '200');
        }
    }

    public function destroy ($id) {
        if (Gate::denies('delete-management-module')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        Module::find($id)->delete();

        return response()->json(['success' => true]);
    }

    public function getFilterValues () {
        $group = Module::select('group')->groupBy('group')->get()->pluck('group')->toArray();
        $page = Module::select('page')->groupBy('page')->get()->pluck('page')->toArray();

        return response()->json([
            'group' => $group,
            'page' => $page
        ], '200');
    }

    private function getUpdatePermissionsData ($old, $new) {
        $create = [];
        $delete = [];

        if ($old != $new) {
            foreach ($new as $newVal) {
                if (!in_array($newVal, $old)) {
                    $create[] = $newVal;
                }
            }

            foreach ($old as $oldVal) {
                if (!in_array($oldVal, $new)) {
                    $delete[] = $oldVal;
                }
            }
        }

        return [
            'create' => $create,
            'delete' => $delete,
        ];
    }
}
