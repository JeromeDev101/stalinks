<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Role;

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

    public function getRoles() {
        // $data = $this->roleRepository->findAll();
        $data = Role::orderBy('name', 'asc')->get();
        return response()->json($data);
    }
}
