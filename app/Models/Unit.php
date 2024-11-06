<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function getItemUnitStatusAttribute()
    {
        return trans('product_statuses')[$this->status] ?? '';
    }
}
