<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use App\Services\Modules\UnitService;
use App\Services\Notifications\UnitNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UnitController extends BackendController
{
    private UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        parent::__construct();

        $this->middleware(['permission:unit'])->only('index');
        $this->middleware(['permission:unit_create'])->only('create', 'store');
        $this->middleware(['permission:unit_edit'])->only('edit', 'update');
        $this->middleware(['permission:unit_destroy'])->only('destroy');

        $this->unitService = $unitService;
    }

    /**
     * Get product unit list
     */
    public function index()
    {
        $this->data['units'] = Unit::latest()->get();

        return view('backend.unit.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.unit.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UnitRequest $request
     * @return mixed
     */
    public function store(UnitRequest $request)
    {
        $unit = $this->unitService->saveUnit(new Unit(), $request);

        app(UnitNotificationService::class)->unitAddedToPermissionUser($unit, auth()->user());

        return redirect(route('admin.unit.index'))->withSuccess('The unit added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['unit'] = Unit::findOrfail($id);

        return view('backend.unit.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UnitRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UnitRequest $request, int $id)
    {
        $unit = Unit::findOrfail($id);

        $this->unitService->saveUnit($unit, $request);

        app(UnitNotificationService::class)->unitUpdatedToPermissionUser($unit, auth()->user());

        return redirect(route('admin.unit.index'))->withSuccess('The unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $unit = Unit::findOrfail($id);
        $unit->delete();

        app(UnitNotificationService::class)->unitDeletedToPermissionUser($unit, auth()->user());

        return redirect(route('admin.unit.index'))->withSuccess('The unit deleted successfully.');
    }
}
