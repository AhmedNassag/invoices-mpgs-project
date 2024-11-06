<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $casts = [
        'date' => 'datetime'
    ];

    public function getReceiptUrlAttribute(): string
    {
        $path = $this->getFirstMediaUrl('expense');

        return !empty($path) ? asset($path) : '';
    }

}
