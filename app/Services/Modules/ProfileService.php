<?php

namespace App\Services\Modules;

use App\Models\User;

class ProfileService
{
    public function saveUser(User $user, $request): User
    {
        $user->name = $request->name;
        $user->designation = $request->designation;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth ?? null;
        $user->joining_date = $request->joining_date ?? null;
        $user->address = $request->address;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return $user;
    }

    public function saveImage($user, $request)
    {
        if ($request->file('image')) {
            $user->media()->delete();
            $user->addMedia($request->file('image'))->toMediaCollection('user');
        }
    }
}