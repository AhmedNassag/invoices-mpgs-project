<?php

namespace App\Services\Modules;

use Spatie\Permission\Models\Permission;

class PermissionService extends BaseService
{
    public function getRoleWisePermission(): array
    {
        $permissionNameList = [];
        $permissionList = [];

        $permissions = Permission::get();
        if (!blank($permissions)) {
            foreach ($permissions as $permission) {
                if (!strpos($permission->name, '_create') && !strpos($permission->name, '_edit') && !strpos($permission->name, '_show') && !strpos($permission->name, '_destroy')) {
                    $permissionList[$permission->id] = $permission;
                }
                $permissionNameList[$permission->name] = $permission->id;
            }
        }

        return [
            'permissionNameList' => $permissionNameList,
            'permissionList' => $permissionList,
        ];
    }

    public function savePermission($role, $permissionIds)
    {
        $permission = Permission::whereIn('id', $permissionIds)->get();

        $role->syncPermissions($permission);
    }
}