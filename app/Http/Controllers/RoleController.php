<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    /**
     * RoleController constructor.
     *
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoles(Request $request) {
        // $data = $this->roleRepository->findAll();
        $data = Role::orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    public function getListRoles(Request $request) {
        $filter = $request->all();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:50;

        $roles = Role::when(isset($filter['name']) && !empty($filter['name']), function($query) use ($filter) {
            return $query->where('name', 'like', '%'.$filter['name'].'%');
        });

        $roles = $roles->with('permissions:id');

        if(isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return response()->json([
                'data' => $roles->get(),
                'total' => $roles->count(),
            ],200);
        } else {
            return $roles->paginate($paginate);
        }

        return response()->json(['data' => $roles],200);
    }

    public function store(Request $request) {
        if (Gate::denies('create-management-role')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $role = Role::create($request->except('permissions'));

        // save permissions
        if (isset($request->permissions) && is_array($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }

        return response()->json(['success' => true], 200);
    }

    public function update(Request $request) {
        if (Gate::denies('update-management-role')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $role = Role::find($request->id);

        $role->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        // save permissions
        if (isset($request->permissions) && is_array($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }

        return response()->json(['success' => true], 200);
    }

    public function delete(Request $request) {
        if (Gate::denies('delete-management-role')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $role = Role::find($request->id);

        // delete permissions

        $role->permissions()->detach();

        $role->update([
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json(['success' => true], 200);
    }
}
