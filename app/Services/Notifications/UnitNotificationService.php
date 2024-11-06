<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class UnitNotificationService extends BaseNotificationService
{
    /**
     * @param $unit
     * @param $loggedInUser
     * @return void
     */
    public function unitAddedToPermissionUser($unit, $loggedInUser): void
    {
        $data = [
            'subject' => "New unit added.",
            'message' => "{LOGGED_USER_NAME} added a new unit records.",
            'url' => route('admin.unit.edit', $unit),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'unit_create');
    }

    /**
     * @param $unit
     * @param $loggedInUser
     * @return void
     */
    public function unitUpdatedToPermissionUser($unit, $loggedInUser): void
    {
        $data = [
            'subject' => "Unit updated.",
            'message' => "{LOGGED_USER_NAME} updated a unit records.",
            'url' => route('admin.unit.edit', $unit),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'unit_edit');
    }

    /**
     * @param $unit
     * @param $loggedInUser
     * @return void
     */
    public function unitDeletedToPermissionUser($unit, $loggedInUser): void
    {
        $data = [
            'subject' => "Unit deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a unit records.",
            'url' => route('admin.unit.edit', $unit),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'unit_destroy');
    }
}