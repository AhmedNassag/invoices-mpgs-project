<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\TaxRateStatus;
use App\Http\Controllers\BackendController;
use App\Http\Requests\QuotationRequest;
use App\Mail\SendQuotationMail;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\InvoiceTax;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\User;
use App\Services\Modules\QuotationService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuotationController extends BackendController
{
    private int $type = InvoiceType::QUOTATION;

    private QuotationService $quotationService;

    public function __construct(QuotationService $quotationService)
    {
        parent::__construct();

        $this->middleware(['permission:quotation'])->only('index');
        $this->middleware(['permission:quotation_create'])->only('create', 'store');
        $this->middleware(['permission:quotation_edit'])->only('edit', 'update');
        $this->middleware(['permission:quotation_destroy'])->only('destroy');
        $this->middleware(['permission:quotation_show'])->only('show');

        $this->quotationService = $quotationService;

        $this->data['isEditPage'] = false;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $quotations = Invoice::query()
            ->with('user')
            ->myinvoice($this->type)
            ->latest()
            ->get();

        $this->data['quotations'] = $this->quotationService->getQuotationList($quotations);


        $this->data['can'] = [
            'quotation_create' => auth()->user()->can('quotation_create'),
            'quotation_show' => auth()->user()->can('quotation_show'),
            'quotation_edit' => auth()->user()->can('quotation_edit'),
            'quotation_destroy' => auth()->user()->can('quotation_destroy'),
            'has_action_permission' => auth()->user()->canAny(['quotation_show', 'quotation_edit', 'quotation_destroy'])
        ];

        return Inertia::render('Quotation/List', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $this->data['isEditPage'] = false;

        $this->data['users'] = User::query()
            ->select('id', 'name')
            ->get()
            ->map(function ($user) {
                return ['label' => $user->name, 'value' => $user->id];
            })->toArray();

        $this->data['products'] = Product::query()
            ->select('id', 'name', 'price')
            ->where('status', ProductStatus::ACTIVE)
            ->get();

        $this->data['taxRates'] = TaxRate::query()
            ->select('id', 'name', 'percent')
            ->where('status', TaxRateStatus::ACTIVE)
            ->get();

        return Inertia::render('Quotation/Create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuotationRequest $request
     * @return RedirectResponse
     */
    public function store(QuotationRequest $request)
    {
        $invoice = $this->quotationService->saveQuotation(new Invoice(), $request);

        $this->quotationService->saveFile($invoice, $request);

        $this->quotationService->saveProduct($invoice, $request);

        $this->quotationService->saveTax($invoice, $request);

        $this->quotationService->calculate($invoice);

        return to_route('admin.quotation.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $this->data['quotation'] = Invoice::query()->myinvoice($this->type)->findOrfail($id);

        $invoiceView = setting('invoicetheme') ?? 'invoice1';

        return view('backend.quotation.' . $invoiceView, $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {
        $this->data['isEditPage'] = true;

        $this->data['quotation'] = Invoice::query()
            ->with('products')
            ->with('taxes')
            ->myinvoice($this->type)
            ->where('payment_status', PaymentStatus::UNPAID)
            ->findOrfail($id);

        $this->data['users'] = User::query()
            ->select('id', 'name')
            ->get()
            ->map(function ($user) {
                return ['label' => $user->name, 'value' => $user->id];
            })->toArray();

        $this->data['products'] = Product::query()
            ->select('id', 'name', 'price')
            ->where('status', ProductStatus::ACTIVE)
            ->get();

        $this->data['taxRates'] = TaxRate::query()
            ->select('id', 'name', 'percent')
            ->where('status', TaxRateStatus::ACTIVE)
            ->get();

        return Inertia::render('Quotation/Edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuotationRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(QuotationRequest $request, int $id)
    {
        $invoice = Invoice::query()
            ->myinvoice($this->type)
            ->where('payment_status', PaymentStatus::UNPAID)
            ->findOrfail($id);

        $invoice->products()->delete();
        $invoice->taxes()->delete();

        $this->quotationService->saveQuotation($invoice, $request);
        $this->quotationService->saveFile($invoice, $request);
        $this->quotationService->saveProduct($invoice, $request);
        $this->quotationService->saveTax($invoice, $request);
        $this->quotationService->calculate($invoice);

        return to_route('admin.quotation.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $quotation = Invoice::query()
            ->myinvoice($this->type)
            ->where('payment_status', PaymentStatus::UNPAID)
            ->findOrfail($id);

        InvoiceTax::where('invoice_id', $quotation->id)->delete();
        InvoiceProduct::where('invoice_id', $quotation->id)->delete();

        $quotation->delete();

        return to_route('admin.quotation.index');
    }

    public function sendQuotation(Request $request, $id)
    {
        $retArray = [
            'status' => false,
            'message' => 'Something wrong, email not send.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        if (!$validator->fails()) {

            $quotation = Invoice::myinvoice($this->type)->find($id);
            if (!blank($quotation)) {
                try {
                    Mail::to($request->email)->send(new SendQuotationMail($quotation, [
                        'message' => $request->get('message'),
                        'url' => route('admin.quotation.share', Crypt::encryptString($id))
                    ]));

                    $retArray['status'] = true;
                } catch (Exception $e) {
                    $retArray['message'] = $e->getMessage();
                }
            } else {
                $retArray['message'] = 'The quotation not found.';
            }
        }

        if ($retArray['status']) {
            Session::flash('success', 'The quotation send successfully.');
        } else {
            Session::flash('error', $retArray['message']);
        }

        echo json_encode($retArray);
    }

    /**
     * @param string $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function share(string $id)
    {
        try {
            $this->data['quotation'] = Invoice::query()
                ->myinvoice($this->type)
                ->findOrfail(Crypt::decryptString($id));

            return view('backend.quotation.share-invoice1', $this->data);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect(route('admin.quotation.index'));
        }
    }

    /**
     * @param int $id
     * @return Application|RedirectResponse|Redirector|BinaryFileResponse
     */
    public function download(int $id)
    {
        $media = Media::query()->findOrfail($id);
        if (blank($media)) {
            return redirect(route('admin.quotation.index'));
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
