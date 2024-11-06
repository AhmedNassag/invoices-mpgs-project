<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\IncomeRequest;
use App\Models\Income;
use App\Services\Modules\IncomeService;
use App\Services\Notifications\IncomeNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IncomeController extends BackendController
{
    private IncomeService $incomeService;

    public function __construct(IncomeService $incomeService)
    {
        parent::__construct();

        $this->middleware(['permission:income'])->only('index');
        $this->middleware(['permission:income_create'])->only('create', 'store');
        $this->middleware(['permission:income_edit'])->only('edit', 'update');
        $this->middleware(['permission:income_destroy'])->only('destroy');

        $this->incomeService = $incomeService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['incomes'] = Income::latest()->get();

        return view('backend.income.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.income.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IncomeRequest $request
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(IncomeRequest $request)
    {
        $income = $this->incomeService->saveIncome(new Income(), $request);

        $this->incomeService->saveReceipt($income, $request);

        app(IncomeNotificationService::class)->incomeAddedToPermissionUser($income, auth()->user());

        return redirect(route('admin.income.index'))->withSuccess('The income added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['income'] = Income::findOrfail($id);

        return view('backend.income.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param IncomeRequest $request
     * @param int $id
     * @return Response
     */
    public function update(IncomeRequest $request, int $id)
    {
        $income = Income::findOrfail($id);

        $this->incomeService->saveIncome($income, $request);

        $this->incomeService->saveReceipt($income, $request);

        app(IncomeNotificationService::class)->incomeUpdatedToPermissionUser($income, auth()->user());

        return redirect(route('admin.income.index'))->withSuccess('The income updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $income = Income::findOrfail($id);
        $income->delete();

        app(IncomeNotificationService::class)->incomeDeletedToPermissionUser($income, auth()->user());

        return redirect(route('admin.income.index'))->withSuccess('The income deleted successfully');
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector|BinaryFileResponse
     */
    public function download(int $id)
    {
        $income = Income::findOrfail($id);
        if (!blank($income->receiptUrl)) {
            $mediaItem = $income->getFirstMedia('income');
            return response()->download($mediaItem->getPath(), $mediaItem->file_name);
        }

        return redirect(route('admin.income.index'));
    }
}
