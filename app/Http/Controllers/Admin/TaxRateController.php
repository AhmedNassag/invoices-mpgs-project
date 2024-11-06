<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\TaxRateRequest;
use App\Models\TaxRate;
use App\Services\Modules\TaxRateService;
use App\Services\Notifications\TaxRateNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class TaxRateController extends BackendController
{
    private TaxRateService $taxRateService;

    public function __construct(TaxRateService $taxRateService)
    {
        parent::__construct();

        $this->middleware(['permission:tax-rate'])->only('index');
        $this->middleware(['permission:tax-rate_create'])->only('create', 'store');
        $this->middleware(['permission:tax-rate_edit'])->only('edit', 'update');
        $this->middleware(['permission:tax-rate_destroy'])->only('destroy');

        $this->taxRateService = $taxRateService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['tateRates'] = TaxRate::latest()->get();

        return view('backend.tax-rate.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.tax-rate.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaxRateRequest $request
     * @return mixed
     */
    public function store(TaxRateRequest $request)
    {
        $taxRate = $this->taxRateService->saveTaxRate(new TaxRate(), $request);

        app(TaxRateNotificationService::class)->taxRateAddedToPermissionUser($taxRate, auth()->user());

        return redirect(route('admin.tax-rate.index'))->withSuccess('The tax rate added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['taxRate'] = TaxRate::findOrfail($id);

        return view('backend.tax-rate.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaxRateRequest $request
     * @param int $id
     * @return Response
     */
    public function update(TaxRateRequest $request, int $id)
    {
        $taxRate = TaxRate::findOrfail($id);

        $this->taxRateService->saveTaxRate($taxRate, $request);

        app(TaxRateNotificationService::class)->taxRateUpdatedToPermissionUser($taxRate, auth()->user());

        return redirect(route('admin.tax-rate.index'))->withSuccess('The tax rate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $taxRate = TaxRate::findOrfail($id);
        $taxRate->delete();

        app(TaxRateNotificationService::class)->taxRateDeletedToPermissionUser($taxRate, auth()->user());

        return redirect(route('admin.tax-rate.index'))->withSuccess('The tax rate deleted successfully.');
    }

}
