<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Services\Modules\PermissionService;
use App\Services\Notifications\RoleNotificationService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PermissionController extends BackendController
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        parent::__construct();

        $this->permissionService = $permissionService;

        $this->middleware(['permission:permission'])->only('index');
    }

    public function index(int $id = 1)
    {
        $role = Role::findOrFail($id);

        $permissions = $this->permissionService->getRoleWisePermission();

        $this->data = array_merge($this->data, $permissions);

        $this->data['roles'] = Role::get();
        $this->data['selectRoleID'] = $role->id;
        $this->data['permissions'] = $role->permissions->pluck('id', 'id');

        return view('backend.permission.index', $this->data);
    }

    public function savePermission(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        if ($_POST) {
            $permissionIds = array_values($request->except(['_token', 'roleID']));

            $this->permissionService->savePermission($role, $permissionIds);

            app(RoleNotificationService::class)->permissionUpdatedToUser($role, auth()->user());
        }
        return redirect(route('admin.permission.index', $role))->withSuccess('The Permission Updated Successfully');
    }
}