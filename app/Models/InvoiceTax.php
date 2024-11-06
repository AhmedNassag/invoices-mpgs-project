<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceTax extends Model
{
    public function tax_rate()
    {
        return $this->belongsTo(TaxRate::class);
    }
}
