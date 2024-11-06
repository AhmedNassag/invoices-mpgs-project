<?php

namespace App\Services\Modules;

use App\Models\Income;

class IncomeService
{
    public function saveIncome(Income $income, $request): Income
    {
        $income->title  = $request->title;
        $income->date   = $request->date;
        $income->amount = $request->amount;
        $income->note   = $request->note;
        $income->save();

        return $income;
    }

    public function saveReceipt($income, $request)
    {
        if ($request->file('receipt')) {
            $income->media()->delete();
            $income->addMedia($request->file('receipt'))->toMediaCollection('income');
        }
    }
}