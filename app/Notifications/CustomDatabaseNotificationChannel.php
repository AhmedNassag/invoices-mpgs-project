<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CustomDatabaseNotificationChannel
{
    public function send($notifiable, Notification $notification)
    {
        $data = $notification->toArray($notifiable);

        $creatorType = data_get($data, 'creator_type');
        $creatorId = data_get($data, 'creator_id');

        unset($data['creator_type'], $data['creator_id']);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'creator_type' => $creatorType,
            'creator_id' => $creatorId,
            'data' => $data,
            'read_at' => null,
        ]);
    }

}