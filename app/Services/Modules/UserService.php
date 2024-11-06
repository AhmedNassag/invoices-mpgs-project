<?php

namespace App\Services\Modules;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserService
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

    public function saveImage($user, $request): void
    {
        if ($request->file('image')) {
            $user->media()->delete();
            $user->addMedia($request->file('image'))->toMediaCollection('user');
        }
    }

    public function saveUserRole($user, $roleId): void
    {
        $role = Role::find($roleId);
        if (!blank($role)) {
            $user->syncRoles([$role->name]);
        }
    }

    public function generateUniqueUsername($name): string
    {
        // Remove any non-alphanumeric characters and convert spaces to underscores
        $cleanedName = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        $cleanedName = str_replace(' ', '_', $cleanedName);

        // Initial username attempt
        $username = strtolower($cleanedName);

        // Check if the username already exists
        $count = User::query()->where('username', $username)->count();

        // If the username already exists, append a number until it's unique
        $i = 1;
        while ($count > 0) {
            $newUsername = $username . $i;
            $count = User::query()->where('username', $newUsername)->count();
            $i++;
        }

        return $newUsername ?? $username;
    }
}