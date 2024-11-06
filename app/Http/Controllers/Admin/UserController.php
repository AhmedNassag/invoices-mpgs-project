<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Modules\UserService;
use App\Services\Notifications\UserNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\Permission\Models\Role;

class UserController extends BackendController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->middleware(['permission:user'])->only('index');
        $this->middleware(['permission:user_create'])->only('create', 'store');
        $this->middleware(['permission:user_edit'])->only('edit', 'update');
        $this->middleware(['permission:user_destroy'])->only('destroy');
        $this->middleware(['permission:user_show'])->only('show');

        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['selectRoleID'] = 0;
        $this->data['users'] = User::latest()->get();
        $this->data['roles'] = Role::get();

        return view('backend.user.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $this->data['roles'] = Role::get();

        return view('backend.user.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        // Save Login User Profile Information
        $user = $this->userService->saveUser(new User(), $request);

        // Save Login User Image Information
        $this->userService->saveImage($user, $request);

        // Save Login User Role Information
        $this->userService->saveUserRole($user, $request->get('role'));

        app(UserNotificationService::class)->userAddedToPermissionUser($user, auth()->user());

        return redirect(route('admin.user.role', $request->role))->withSuccess('The user added successfully.');
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(int $id)
    {
        $this->data['user'] = User::findOrfail($id);
        $this->data['roles'] = Role::get();

        return view('backend.user.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['user'] = User::findOrfail($id);
        $this->data['roles'] = Role::get();

        return view('backend.user.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UserRequest $request, int $id)
    {
        $user = User::findOrfail($id);

        // Save Login User Profile Information
        $this->userService->saveUser($user, $request);

        // Save Login User Image Information
        $this->userService->saveImage($user, $request);

        // Save Login User Role Information
        $this->userService->saveUserRole($user, $request->get('role'));

        app(UserNotificationService::class)->userUpdatedToPermissionUser($user, auth()->user());

        return redirect(route('admin.user.role', $request->role))->withSuccess('The user updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        if ($id == 1) {
            return redirect(route('admin.user.index'))->withError('You don\'t have permission to delete administrator.');
        }

        $user = User::where('id', '!=', 1)->findOrfail($id);
        $user->delete();

        app(UserNotificationService::class)->userDeletedToPermissionUser($user, auth()->user());

        return redirect(route('admin.user.index'))->withSuccess('The user deleted successfully.');
    }

    /**
     * Get role wise user list
     * @param int $roleId
     * @return Application|Factory|View
     */
    public function getRoleUser(int $roleId = 0)
    {
        $role = Role::findOrFail($roleId);

        if ($roleId != 0) {
            $this->data['users'] = User::role($role->name)->get();
        } else {
            $this->data['users'] = User::get();
        }
        $this->data['selectRoleID'] = $roleId;
        $this->data['roles'] = Role::get();

        return view('backend.user.index', $this->data);
    }
}
