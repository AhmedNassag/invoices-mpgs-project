<?php
namespace App\Services\Modules;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Invoice;
use Carbon\Carbon;

class DashboardService {

    public function getDashboardTiles(): array
    {
        $lastSevenDay = [Carbon::now(), Carbon::now()->subDays(7)];

        $dailyInvoices = Invoice::myinvoice()->whereDate('date', Carbon::today())->get();
        $weeklyInvoices = Invoice::myinvoice()->whereBetween('date', $lastSevenDay)->get();

        $dailyQuotationTotalAmount = Invoice::myinvoice(10)->whereDate('date', Carbon::today())->sum('total_amount');
        $weeklyQuotationTotalAmount = Invoice::myinvoice(10)->whereBetween('date', $lastSevenDay)->sum('total_amount');

        $dailyInvoiceTotalAmount = 0;
        $dailyInvoicePaymentAmount = 0;
        if (!blank($dailyInvoices)) {
            foreach ($dailyInvoices as $dailyInvoice) {
                $dailyInvoiceTotalAmount += $dailyInvoice->total_amount;
                $dailyInvoicePaymentAmount += $dailyInvoice->payment_amount;
            }
        }

        $weeklyInvoiceTotalAmount = 0;
        $weeklyInvoicePaymentAmount = 0;
        if (!blank($weeklyInvoices)) {
            foreach ($weeklyInvoices as $weeklyInvoice) {
                $weeklyInvoiceTotalAmount += $weeklyInvoice->total_amount;
                $weeklyInvoicePaymentAmount += $weeklyInvoice->payment_amount;
            }
        }

        return [
            'dailyInvoiceTotalAmount' => $dailyInvoiceTotalAmount,
            'dailyInvoicePaymentAmount' => $dailyInvoicePaymentAmount,
            'weeklyInvoiceTotalAmount' => $weeklyInvoiceTotalAmount,
            'weeklyInvoicePaymentAmount' => $weeklyInvoicePaymentAmount,
            'dailyQuotationTotalAmount' => $dailyQuotationTotalAmount,
            'weeklyQuotationTotalAmount' => $weeklyQuotationTotalAmount,
        ];
    }

    /**
     * Get Income/Expense
     * @return array
     */
    public function getIncomeExpenseYearlyList(): array
    {
        $fromDate = date('Y-m-d', strtotime('01-01-' . date("Y"))) . ' 00:00:00';
        $toDate = date('Y-m-d', strtotime('31-12-' . date("Y"))) . ' 23:59:59';

        $incomes = Income::whereBetween('date', [$fromDate, $toDate])->get();
        $expenses = Expense::whereBetween('date', [$fromDate, $toDate])->get();

        $incomeSummary = $this->getAccountAmountSummary($incomes);
        $expenseSummary = $this->getAccountAmountSummary($expenses);

        return [
            'income' => $incomeSummary['month_wise_amount'],
            'expense' => $expenseSummary['month_wise_amount'],
            'totalIncomeAmount' => $incomeSummary['total_amount'],
            'totalExpenseAmount' => $expenseSummary['total_amount']
        ];
    }

    /**
     * Calculate income/expense
     * @param $accounts
     * @return array
     */
    private function getAccountAmountSummary($accounts): array
    {
        $monthArr = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];

        $totalAmount = 0;
        if (!blank($accounts)) {
            foreach ($accounts as $account) {
                $month = (int)date('m', strtotime($account->date));
                $monthArr[$month] += $account->amount;

                $totalAmount += $account->amount;
            }
        }

        return [
            'month_wise_amount' => implode(',', $monthArr),
            'total_amount' => $totalAmount
        ];
    }
}