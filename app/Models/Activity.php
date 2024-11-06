<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as BaseActivity;
/**
 * @property mixed $created_at
 */
class Activity extends BaseActivity
{
    public function getCreateDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d M Y H:i:s A') : '';
    }
}
