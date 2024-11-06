<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class IncomeNotificationService extends BaseNotificationService
{
    /**
     * @param $income
     * @param $loggedInUser
     * @return void
     */
    public function incomeAddedToPermissionUser($income, $loggedInUser): void
    {
        $data = [
            'subject' => "New income added.",
            'message' => "{LOGGED_USER_NAME} added a new income records.",
            'url' => route('admin.income.index', $income),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'income_create');
    }

    /**
     * @param $income
     * @param $loggedInUser
     * @return void
     */
    public function incomeUpdatedToPermissionUser($income, $loggedInUser): void
    {
        $data = [
            'subject' => "Income updated.",
            'message' => "{LOGGED_USER_NAME} updated a income records.",
            'url' => route('admin.income.index', $income),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'income_edit');
    }

    /**
     * @param $income
     * @param $loggedInUser
     * @return void
     */
    public function incomeDeletedToPermissionUser($income, $loggedInUser): void
    {
        $data = [
            'subject' => "Income deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a income records.",
            'url' => route('admin.income.index', $income),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'income_destroy');
    }
}