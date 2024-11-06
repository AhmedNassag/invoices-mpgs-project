<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\RoleRequest;
use App\Services\Notifications\RoleNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class RoleController extends BackendController
{
    private array $defaultRoleIds = [1, 2, 3];

    function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:role'])->only('index');
        $this->middleware(['permission:role_create'])->only('create', 'store');
        $this->middleware(['permission:role_edit'])->only('edit', 'update');
        $this->middleware(['permission:role_destroy'])->only('destroy');

        $this->data['defaultRoleIds'] = $this->defaultRoleIds;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['roles'] = Role::get();

        return view('backend.role.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.role.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return mixed
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->get('name');
        $role->save();

        app(RoleNotificationService::class)->roleAddedToPermissionUser($role, auth()->user());

        return redirect(route('admin.role.index'))->withSuccess('The role Added Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['role'] = Role::findOrfail($id);

        return view('backend.role.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(RoleRequest $request, int $id)
    {
        $role = Role::findOrfail($id);

        $role->name = $request->get('name');
        $role->save();

        app(RoleNotificationService::class)->roleUpdatedToPermissionUser($role, auth()->user());

        return redirect(route('admin.role.index'))->withSuccess('The role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        if (in_array($id, $this->defaultRoleIds)) {
            return redirect(route('admin.role.index'))->withError('The role can\'t be delete.');
        }

        $role = Role::findOrfail($id);
        $role->delete();

        app(RoleNotificationService::class)->roleDeletedToPermissionUser($role, auth()->user());

        return redirect(route('admin.role.index'))->withSuccess('The role deleted successfully.');
    }
}
