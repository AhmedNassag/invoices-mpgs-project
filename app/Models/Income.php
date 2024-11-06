<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Income extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'date' => 'datetime'
    ];

    public function getReceiptUrlAttribute(): string
    {
        $path = $this->getFirstMediaUrl('income');

        return !empty($path) ? asset($path) : '';
    }

}
