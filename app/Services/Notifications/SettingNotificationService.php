<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class SettingNotificationService extends BaseNotificationService
{
    /**
     * @param $loggedInUser
     * @return void
     */
    public function generalSettingUpdatedToPermissionUser($loggedInUser): void
    {
        $data = [
            'subject' => "General setting updated.",
            'message' => "{LOGGED_USER_NAME} updated system basic information.",
            'url' => route('admin.setting.index'),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'setting');
    }

    /**
     * @param $loggedInUser
     * @return void
     */
    public function invoiceSettingUpdatedToPermissionUser($loggedInUser): void
    {
        $data = [
            'subject' => "Invoice setting updated.",
            'message' => "{LOGGED_USER_NAME} updated system invoice theme.",
            'url' => route('admin.setting.index'),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'setting');
    }

}