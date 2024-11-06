<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function getStatusLabelAttribute()
    {
        return data_get(trans('product_statuses'), $this->status, '');
    }

    public function getImageAttribute(): string
    {
        if (!empty($this->getFirstMediaUrl('item'))) {
            return asset($this->getFirstMediaUrl('item'));
        }
        return asset('img/item.png');
    }
}
