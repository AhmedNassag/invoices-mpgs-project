<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class TaxRateNotificationService extends BaseNotificationService
{
    /**
     * @param $taxRate
     * @param $loggedInUser
     * @return void
     */
    public function taxRateAddedToPermissionUser($taxRate, $loggedInUser): void
    {
        $data = [
            'subject' => "New tax rate added.",
            'message' => "{LOGGED_USER_NAME} added a new taxRate records.",
            'url' => route('admin.tax-rate.edit', $taxRate),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'tax-rate');
    }

    /**
     * @param $taxRate
     * @param $loggedInUser
     * @return void
     */
    public function taxRateUpdatedToPermissionUser($taxRate, $loggedInUser): void
    {
        $data = [
            'subject' => "Tax rate updated.",
            'message' => "{LOGGED_USER_NAME} updated a tax rate records.",
            'url' => route('admin.tax-rate.edit', $taxRate),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'tax-rate');
    }

    /**
     * @param $taxRate
     * @param $loggedInUser
     * @return void
     */
    public function taxRateDeletedToPermissionUser($taxRate, $loggedInUser): void
    {
        $data = [
            'subject' => "Tax rate deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a taxRate records.",
            'url' => route('admin.tax-rate.edit', $taxRate),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'tax-rate');
    }
}