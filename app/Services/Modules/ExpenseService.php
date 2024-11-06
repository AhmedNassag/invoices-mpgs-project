<?php

namespace App\Services\Modules;

use App\Models\Expense;

class ExpenseService
{
    public function saveExpense(Expense $expense, $request): Expense
    {
        $expense->title = $request->title;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->note = $request->note;
        $expense->save();

        return $expense;
    }

    public function saveReceipt($expense, $request)
    {
        if ($request->file('receipt')) {
            $expense->media()->delete();
            $expense->addMedia($request->file('receipt'))->toMediaCollection('expense');
        }
    }
}