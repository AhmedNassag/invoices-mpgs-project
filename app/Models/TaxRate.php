<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TaxRate extends Model
{
    use LogsActivity;

    public $appends = ['name_with_percent'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function getTaxStatusAttribute() {
        return data_get(trans('tax_statuses'), $this->status, '');
    }

    public function getNameWithPercentAttribute(): string
    {
    	return $this->name . '(' . $this->percent . '%)';
    }
}
