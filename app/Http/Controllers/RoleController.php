<?php

namespace App\Http\Controllers;

use App\Cache\PermissionCacheRepository;
use App\Cache\RoleCacheRepository;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    protected $permissionRepository;
    protected $role;

    public function setRole(Request $request)
    {
        $role_id = $request->route('role_id');

        if ($role_id) {
            $this->role = $this->repository?->getById($role_id);
        }
    }

    public function __construct(RoleCacheRepository $roleCache, PermissionCacheRepository $permissionCache, Request $request)
    {
        $this->repository = $roleCache;
        $this->permissionRepository = $permissionCache;
        $this->setRole($request);
    }

    public function create(Request $request)
    {
        abort_unless($request->user()?->can('create roles'), 403);

        $permissions = $this->permissionRepository->all();

        return Inertia::render('Role/Create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        abort_unless($request->user()?->can('create roles'), 403);

        $role = Role::create($request->only('name'));

        $role->syncPermissions($request->input('permissions_ids'));

        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        abort_unless($request->user()?->can('remove roles'), 403);

        $this->role->delete();

        return redirect()->back();
    }

    public function index(Request $request)
    {
        abort_unless($request->user()?->can('view roles'), 403);

        $roles = $this->repository->paginate(10);

        return Inertia::render('Role/Index', compact('roles'));
    }

    public function edit(Request $request)
    {
        abort_unless($request->user()?->can('edit roles'), 403);

        return Inertia::render('Role/Edit', ['role' => $this->role]);
    }

    public function update(UpdateRoleRequest $request)
    {
        abort_unless($request->user()?->can('edit roles'), 403);

        $this->repository->update($request->only(['name']), $this->role->id);

        return redirect()->back();
    }
}
