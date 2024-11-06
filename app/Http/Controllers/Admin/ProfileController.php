<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\ProfileRequest;
use App\Services\Modules\ProfileService;

class ProfileController extends BackendController
{
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        parent::__construct();

        $this->profileService = $profileService;
    }

    public function index()
    {
        $this->data['user'] = auth()->user();

        return view('backend.profile.index', $this->data);
    }

    public function update(ProfileRequest $request)
    {
        $this->profileService->saveUser(auth()->user(), $request);

        $this->profileService->saveImage(auth()->user(), $request);

        return redirect(route('admin.profile.index'))->withSuccess('Your profile updated successfully.');
    }
}
