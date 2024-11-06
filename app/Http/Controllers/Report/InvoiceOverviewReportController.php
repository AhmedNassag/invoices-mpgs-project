<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\BackendController;
use App\Models\Invoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceOverviewReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:invoice-overview-report']);
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $this->data['showView'] = false;
        $this->data['set_user_id'] = 0;
        $this->data['set_from_date'] = date('d-m-Y');
        $this->data['set_to_date'] = date('d-m-Y');

        $this->data['users'] = User::select('id', 'name')->get();

        if ($_POST) {
            $request->validate([
                'user_id' => 'required|numeric',
                'from_date' => 'nullable|date',
                'to_date' => 'nullable|date|after_or_equal:from_date',
            ]);

            $this->data['showView'] = true;
            $this->data['set_user_id'] = $request->integer('user_id');
            $this->data['set_from_date'] = $request->from_date;
            $this->data['set_to_date'] = $request->to_date;

            $queryArray = [];
            if ($request->integer('user_id')) {
                $queryArray['user_id'] = $request->integer('user_id');
            }

            $dateBetween = [];
            if ($request->from_date != '' && $request->to_date != '') {
                $dateBetween['from_date'] = date('Y-m-d', strtotime($request->from_date)) . ' 00:00:00';
                $dateBetween['to_date'] = date('Y-m-d', strtotime($request->to_date)) . ' 23:59:59';
            }

            if (!blank($dateBetween)) {
                $this->data['invoices'] = Invoice::where($queryArray)->whereBetween('date', [$dateBetween['from_date'], $dateBetween['to_date']])->myinvoice()->get();
            } else {
                $this->data['invoices'] = Invoice::where($queryArray)->myinvoice()->get();
            }
        }

        return view('backend.report.invoice-overview.index', $this->data);
    }

    public function pdf($userId, $fromDate, $toDate)
    {
        $queryArray = [];
        if ($userId) {
            $queryArray['user_id'] = $userId;
        }

        $dateBetween = [];
        if ($fromDate && $toDate) {
            $dateBetween['from_date'] = date('Y-m-d', $fromDate) . ' 00:00:00';
            $dateBetween['to_date'] = date('Y-m-d', $toDate) . ' 23:59:59';
        }

        if (!blank($dateBetween)) {
            $this->data['invoices'] = Invoice::where($queryArray)->whereBetween('date', [$dateBetween['from_date'], $dateBetween['to_date']])->myinvoice()->get();
        } else {
            $this->data['invoices'] = Invoice::where($queryArray)->myinvoice()->get();
        }


        return Pdf::loadView('dompdf.invoice-overview-report', $this->data)->stream();
    }

}
