<?php

namespace App\Services\Modules;

use App\Models\TaxRate;

class TaxRateService
{

    public function saveTaxRate(TaxRate $taxRate, $request): TaxRate
    {

        $taxRate->name = $request->name;
        $taxRate->percent = $request->percent;
        $taxRate->description = $request->description;
        $taxRate->status = $request->status;
        $taxRate->save();

        return $taxRate;
    }
}