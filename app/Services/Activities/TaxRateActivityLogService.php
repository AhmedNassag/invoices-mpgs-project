<?php

namespace App\Services\Activities;

class TaxRateActivityLogService extends BaseActivityLogService
{
    public function createTaxRate($taxRate, $description = null, $misc= []) {
            activity()->useLog('create')
            ->performedOn($taxRate)
            ->causedBy(auth()->user())
            ->withProperties($misc)
            ->log($description);
    }

    public function updateTaxRate($taxRate, $description = null, $misc= []) {
        activity()->useLog('update')
            ->performedOn($taxRate)
            ->causedBy(auth()->user())
            ->withProperties($misc)
            ->log($description);
    }

    public function deleteTaxRate($taxRate, $description = null, $misc= []) {
        activity()->useLog('delete')
            ->performedOn($taxRate)
            ->causedBy(auth()->user())
            ->withProperties($misc)
            ->log($description);
    }
}
