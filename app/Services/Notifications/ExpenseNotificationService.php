<?php

namespace App\Services\Notifications;

use App\Enums\NotificationType;

class ExpenseNotificationService extends BaseNotificationService
{
    /**
     * @param $expense
     * @param $loggedInUser
     * @return void
     */
    public function expenseAddedToPermissionUser($expense, $loggedInUser): void
    {
        $data = [
            'subject' => "New expense added.",
            'message' => "{LOGGED_USER_NAME} added a new expense records.",
            'url' => route('admin.expense.edit', $expense),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::CREATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'expense_create');
    }

    /**
     * @param $expense
     * @param $loggedInUser
     * @return void
     */
    public function expenseUpdatedToPermissionUser($expense, $loggedInUser): void
    {
        $data = [
            'subject' => "Expense updated.",
            'message' => "{LOGGED_USER_NAME} updated a expense records.",
            'url' => route('admin.expense.edit', $expense),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::UPDATED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'expense_edit');
    }

    /**
     * @param $expense
     * @param $loggedInUser
     * @return void
     */
    public function expenseDeletedToPermissionUser($expense, $loggedInUser): void
    {
        $data = [
            'subject' => "Expense deleted.",
            'message' => "{LOGGED_USER_NAME} deleted a expense records.",
            'url' => route('admin.expense.edit', $expense),
            'icon' => 'fas fa-file-invoice-dollar',
            'type' => NotificationType::DELETED,
            'keys' => [
                'LOGGED_USER_NAME' => data_get($loggedInUser, 'name'),
            ]
        ];

        $this->sendNotificationToPermissionUser($data, $loggedInUser, 'expense_destroy');
    }
}