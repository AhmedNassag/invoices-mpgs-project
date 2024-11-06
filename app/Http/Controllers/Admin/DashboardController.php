<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Invoice;
use App\Services\Modules\DashboardService;

class DashboardController extends BackendController
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        parent::__construct();
        $this->middleware(['permission:dashboard'])->only('index');

        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $getDashboardTiles = $this->dashboardService->getDashboardTiles();
        $getIncomeExpenseList = $this->dashboardService->getIncomeExpenseYearlyList();
        $invoices =  Invoice::myinvoice()->latest()->take(10)->get();


        $this->data = array_merge($this->data, ['dataInfo' => $getDashboardTiles], $getIncomeExpenseList, ['invoices' => $invoices]);

        return view('backend.dashboard.index', $this->data);
    }
}
