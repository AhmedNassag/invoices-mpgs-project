<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class UserNotificationService extends BaseNotificationService
{
    /**
     * @param $user
     * @param $loggedInUser
     * @return void
     */
    public function userAddedToPermissionUser($user, $loggedInUser): void
    {
        $data = [
            'subject' => "New user added.",
            'message' => "{LOGGED_USER_NAME} added a new user records.",
            'url' => route('admin.user.edit', $user),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'user_create');
    }

    /**
     * @param $user
     * @param $loggedInUser
     * @return void
     */
    public function userUpdatedToPermissionUser($user, $loggedInUser): void
    {
        $data = [
            'subject' => "User updated.",
            'message' => "{LOGGED_USER_NAME} updated a user records.",
            'url' => route('admin.user.edit', $user),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'user_edit');
    }

    /**
     * @param $user
     * @param $loggedInUser
     * @return void
     */
    public function userDeletedToPermissionUser($user, $loggedInUser): void
    {
        $data = [
            'subject' => "User deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a user records.",
            'url' => route('admin.user.edit', $user),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'user_destroy');
    }
}