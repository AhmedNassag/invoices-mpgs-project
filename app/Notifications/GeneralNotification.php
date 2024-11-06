<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public array $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return [CustomDatabaseNotificationChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type' => data_get($this->data, 'type', NotificationType::CREATED),
            'creator_type' => data_get($this->data, 'creator_type'),
            'creator_id' => data_get($this->data, 'creator_id'),
            'keys' => data_get($this->data, 'keys', []),
            'subject' => data_get($this->data, 'subject'),
            'message' => data_get($this->data, 'message'),
            'icon' => data_get($this->data, 'icon', 'fas fa-bell fa-fw'),
            'url' => data_get($this->data, 'url', '#')
        ];
    }
}