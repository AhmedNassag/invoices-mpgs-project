<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;
use App\Models\User;

class RoleNotificationService extends BaseNotificationService
{
    /**
     * @param $role
     * @param $loggedInUser
     * @return void
     */
    public function roleAddedToPermissionUser($role, $loggedInUser): void
    {
        $data = [
            'subject' => "New role added.",
            'message' => "{LOGGED_USER_NAME} added a new role records.",
            'url' => route('admin.role.edit', $role),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'role_create');
    }

    /**
     * @param $role
     * @param $loggedInUser
     * @return void
     */
    public function roleUpdatedToPermissionUser($role, $loggedInUser): void
    {
        $data = [
            'subject' => "Income updated.",
            'message' => "{LOGGED_USER_NAME} updated a role records.",
            'url' => route('admin.role.edit', $role),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'role_edit');
    }

    /**
     * @param $role
     * @param $loggedInUser
     * @return void
     */
    public function roleDeletedToPermissionUser($role, $loggedInUser): void
    {
        $data = [
            'subject' => "Income deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a role records.",
            'url' => route('admin.role.edit', $role),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'role_destroy');
    }

    /**
     * @param $role
     * @param $loggedInUser
     * @return void
     */
    public function permissionUpdatedToUser($role, $loggedInUser): void
    {
        $data = [
            'subject' => "Permission updated.",
            'message' => "{LOGGED_USER_NAME} updated your permission.",
            'url' => route('admin.role.edit', $role),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        User::query()->role($role->name)->get()->map(function($user) use ($data, $loggedInUser) {
            $this->sendNotificationToUser($data, $user, $loggedInUser);
        });
    }
}