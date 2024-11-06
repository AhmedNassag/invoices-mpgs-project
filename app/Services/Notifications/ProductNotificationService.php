<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class ProductNotificationService extends BaseNotificationService
{
    /**
     * @param $product
     * @param $loggedInUser
     * @return void
     */
    public function productAddedToPermissionUser($product, $loggedInUser): void
    {
        $data = [
            'subject' => "New product added.",
            'message' => "{LOGGED_USER_NAME} added a new product records.",
            'url' => route('admin.product.edit', $product),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'product_create');
    }

    /**
     * @param $product
     * @param $loggedInUser
     * @return void
     */
    public function productUpdatedToPermissionUser($product, $loggedInUser): void
    {
        $data = [
            'subject' => "Income updated.",
            'message' => "{LOGGED_USER_NAME} updated a product records.",
            'url' => route('admin.product.edit', $product),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'product_edit');
    }

    /**
     * @param $product
     * @param $loggedInUser
     * @return void
     */
    public function productDeletedToPermissionUser($product, $loggedInUser): void
    {
        $data = [
            'subject' => "Income deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a product records.",
            'url' => route('admin.product.edit', $product),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'product_destroy');
    }
}