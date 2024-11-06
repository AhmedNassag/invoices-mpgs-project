<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }


    public function getIsDeletedAttribute(): bool
    {
        return data_get($this->data, 'type') !== NotificationType::DELETED;
    }

    public function getLabelCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d M Y h:i A') : '';
    }

    public function getUrlAttribute()
    {
        return data_get($this->data, 'url', route('admin.notification.index'));
    }
}