<?php

namespace App\Services\Notifications;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Exception;
use Illuminate\Support\Facades\Log;

class BaseNotificationService
{
    /**
     * @param $data
     * @param $loggedInUser
     * @param $permission
     * @return void
     */
    public function sendNotificationToPermissionUser($data, $loggedInUser, $permission): void
    {
        try {
            $this->_sendNotificationToPermissionUser($data, $loggedInUser, $permission);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $data
     * @param $user
     * @param $loggedInUser
     * @return void
     */
    public function sendNotificationToUser($data, $user, $loggedInUser): void
    {
        try {
            $this->_sendNotificationToUser($data, $user, $loggedInUser);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $data
     * @param $loggedInUser
     * @param $permission
     * @return void
     */
    private function _sendNotificationToPermissionUser($data, $loggedInUser, $permission): void
    {
        $users = User::query()->where('id', '!=', $loggedInUser->id)->get();

        if (!blank($users)) {
            foreach ($users as $user) {
                if ($user->can($permission)) {
                    $this->_sendNotificationToUser($data, $user, $loggedInUser);
                }
            }
        }
    }

    /**
     * @param $data
     * @param $user
     * @param $loggedInUser
     * @return void
     */
    private function _sendNotificationToUser($data, $user, $loggedInUser): void
    {
        $data = array_merge($data, ['creator_type' => get_class($loggedInUser), 'creator_id' => data_get($loggedInUser, 'id')]);

        $user->notify(new GeneralNotification($data));
    }
}