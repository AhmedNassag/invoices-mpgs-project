<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\Modules\ExpenseService;
use App\Services\Notifications\ExpenseNotificationService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExpenseController extends BackendController
{

    private ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        parent::__construct();

        $this->middleware(['permission:expense'])->only('index');
        $this->middleware(['permission:expense_create'])->only('create', 'store');
        $this->middleware(['permission:expense_edit'])->only('edit', 'update');
        $this->middleware(['permission:expense_destroy'])->only('destroy');

        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['expenses'] = Expense::latest()->get();

        return view('backend.expense.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.expense.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExpenseRequest $request
     * @return Response
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(ExpenseRequest $request)
    {
        $expense = $this->expenseService->saveExpense(new Expense, $request);

        $this->expenseService->saveReceipt($expense, $request);

        app(ExpenseNotificationService::class)->expenseAddedToPermissionUser($expense, auth()->user());

        return redirect(route('admin.expense.index'))->withSuccess('The expense added successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $this->data['expense'] = Expense::findOrfail($id);

        return view('backend.expense.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id)
    {
        $expense = Expense::findOrfail($id);

        $this->expenseService->saveExpense($expense, $request);

        $this->expenseService->saveReceipt($expense, $request);


        app(ExpenseNotificationService::class)->expenseUpdatedToPermissionUser($expense, auth()->user());

        return redirect(route('admin.expense.index'))->withSuccess('The expense updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        $expense = Expense::findOrfail($id);
        $expense->delete();

        app(ExpenseNotificationService::class)->expenseDeletedToPermissionUser($expense, auth()->user());

        return redirect(route('admin.expense.index'))->withSuccess('The expense deleted successfully');
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector|BinaryFileResponse
     */
    public function download(int $id)
    {
        $expense = Expense::findOrfail($id);
        if (!blank($expense->receiptUrl)) {
            $mediaItem = $expense->getFirstMedia('expense');
            return response()->download($mediaItem->getPath(), $mediaItem->file_name);
        }

        return redirect(route('admin.expense.index'));
    }
}
