<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\TaxRateStatus;
use App\Http\Controllers\BackendController;
use App\Http\Requests\QuotationRequest;
use App\Mail\SendInvoiceMail;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\PaymentLog;
use App\Models\InvoiceProduct;
use App\Models\InvoiceTax;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\User;
use App\Services\Modules\InvoiceService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class InvoiceController extends BackendController
{

    private InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {

        parent::__construct();

        $this->middleware(['permission:invoice'])->only('index');
        $this->middleware(['permission:invoice_create'])->only('create', 'store');
        $this->middleware(['permission:invoice_edit'])->only('edit', 'update');
        $this->middleware(['permission:invoice_destroy'])->only('destroy');
        $this->middleware(['permission:invoice_show'])->only('show', 'payment', 'paymentCreate');

        $this->invoiceService = $invoiceService;

        $this->data['paymentMethod'] = [
            'stripe' => PaymentMethod::STRIPE,
            'paypal' => PaymentMethod::PAYPAL,
            'razorpay' => PaymentMethod::RAZORPAY,
        ];

        $this->data['isEditPage'] = false;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $invoices = Invoice::query()
            ->with('user')
            ->myinvoice()
            ->latest()
            ->get();

        $this->data['invoices'] = $this->invoiceService->getInvoiceList($invoices);

        $this->data['can'] = [
            'invoice_create' => auth()->user()->can('invoice_create'),
            'invoice_show' => auth()->user()->can('invoice_show'),
            'invoice_edit' => auth()->user()->can('invoice_edit'),
            'invoice_destroy' => auth()->user()->can('invoice_destroy'),
            'has_action_permission' => auth()->user()->canAny(['invoice_show', 'invoice_edit', 'invoice_destroy'])
        ];

        return Inertia::render('Invoice/List', $this->data);
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

        return Inertia::render('Invoice/Create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuotationRequest $request
     * @return RedirectResponse
     */
    public function store(QuotationRequest $request)
    {
        $invoice = $this->invoiceService->saveInvoice(new Invoice(), $request);

        $this->invoiceService->saveFile($invoice, $request);

        $this->invoiceService->saveTax($invoice, $request);

        $this->invoiceService->saveProduct($invoice, $request);

        $this->invoiceService->calculate($invoice);

        return to_route('admin.invoice.index');
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $this->data['invoice'] = Invoice::query()
            ->myinvoice()
            ->findOrfail($id);

        $invoiceView = setting('invoicetheme') ?? 'invoice1';

        return view('backend.invoice.' . $invoiceView, $this->data);
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

        $this->data['invoice'] = Invoice::query()
            ->with('products')
            ->with('taxes')
            ->myinvoice()
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

        return Inertia::render('Invoice/Edit', $this->data);
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
            ->myinvoice()
            ->where('payment_status', PaymentStatus::UNPAID)
            ->findOrfail($id);

        $invoice->products()->delete();
        $invoice->taxes()->delete();


        $invoice = $this->invoiceService->saveInvoice($invoice, $request);

        $this->invoiceService->saveFile($invoice, $request);

        $this->invoiceService->saveTax($invoice, $request);

        $this->invoiceService->saveProduct($invoice, $request);

        $this->invoiceService->calculate($invoice);

        return to_route('admin.invoice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $invoice = Invoice::query()
            ->myinvoice()
            ->where('payment_status', PaymentStatus::UNPAID)
            ->findOrfail($id);

        InvoiceTax::where('invoice_id', $invoice->id)->delete();
        InvoiceProduct::where('invoice_id', $invoice->id)->delete();

        $invoice->delete();

        return to_route('admin.invoice.index');
    }


    /**
     * @param Request $request
     * @param $id
     * @return void
     */
    public function sendInvoice(Request $request, $id)
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

            $invoice = Invoice::myinvoice()->find($id);
            if (!blank($invoice)) {
                try {
                    Mail::to($request->email)->send(new SendInvoiceMail($invoice, [
                        'message' => $request->get('message'),
                        'url' => route('admin.invoice.share', Crypt::encryptString($id))
                    ]));

                    $retArray['status'] = true;
                } catch (Exception $e) {
                    Log::error($e);
                    $retArray['message'] = $e->getMessage();
                }
            } else {
                $retArray['message'] = 'The invoice not found.';
            }
        }

        if ($retArray['status']) {
            Session::flash('success', 'The invoice send successfully.');
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
            $this->data['invoice'] = Invoice::query()
                ->myinvoice()
                ->findOrfail(Crypt::decryptString($id));

            return view('backend.invoice.share-invoice1', $this->data);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect(route('admin.invoice.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|RedirectResponse|Redirector|BinaryFileResponse
     */
    public function download(int $id)
    {
        $media = Media::query()->findOrfail($id);
        if (blank($media)) {
            return redirect(route('admin.invoice.index'));
        }

        return response()->download($media->getPath(), $media->file_name);
    }



    public function create_checkout_session(Request $request)
    {
        $invoice = Invoice::where('uuid', $request->uuid)->first();
        $currency_code = Setting::where('key', 'currency_code')->first()->value;
    
        // Your API credentials
        $merchantId = Setting::where('key', 'MERCHANT_ID')->first()->value;
        $apiUsername = 'merchant.' . $merchantId;
        $apiPassword = Setting::where('key', 'API_PASSWORD')->first()->value;
    
        // API endpoint URL for creating a session
        $url = 'https://cibpaynow.gateway.mastercard.com/api/rest/version/57/merchant/' . $merchantId . '/session';
    
        // Payment data
        $data = [
            "apiOperation" => "CREATE_CHECKOUT_SESSION",
            "order" => [
                "id" => "order_" . uniqid(),
                "amount" => $invoice->total_amount, // Payment amount
                "currency" => $currency_code // Currency code
            ],
            "interaction" => [
                "operation" => "PURCHASE",
                 'returnUrl' => route('checkout.success'),
                 'cancelUrl' => route('checkout.error')
            ]
        ];
        // Initialize cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($apiUsername . ':' . $apiPassword)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
        // Execute request
        $response = curl_exec($ch);
    
        // Check for cURL errors
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
    
            // Log the error for debugging
            \Log::error('cURL error during checkout session creation: ' . $error_msg);
    
            return response()->json([
                "status" => "error",
                "message" => "Request failed: " . $error_msg
            ], 500);
        }
    
        curl_close($ch);
    
        // Process response
        $responseData = json_decode($response, true);
    
        // Check if the session ID is returned
        if (isset($responseData['session']['id'])) {
            return response()->json([
                "status" => "success",
                "sessionId" => $responseData['session']['id']
            ]);
        } else {
            // Log the API error for debugging
            \Log::error('Error in response from Mastercard API: ' . $response);
    
            return response()->json([
                "status" => "error",
                "message" => "Failed to create session",
                "details" => $responseData
            ], 500);
        }
    }
    



    public function checkout($uuid)
    {
        return view('backend.invoice.checkout',compact('uuid'));
    }
    public function verify(Request $request) {

         // Your API credentials
        $merchantId = Setting::where('key', 'MERCHANT_ID')->first()->value;
        $apiUsername = 'merchant.' . $merchantId;
        $apiPassword = Setting::where('key', 'API_PASSWORD')->first()->value;
        $invoice = Invoice::where('uuid', $request->uuid)->first();
        // Retrieve transaction ID from the request
        $transactionId = $request->input('transactionId');

        if (!$transactionId) {
            return response()->json(['status' => 'error', 'message' => 'Transaction ID is required'], 400);
        }

        // Mastercard API endpoint to verify payment status
        $url = 'https://cibpaynow.gateway.mastercard.com/api/rest/version/57/merchant/' . $merchantId . '/order/' . $transactionId;

        // Send the request to verify the payment
        $response = Http::withBasicAuth($apiUsername, $apiPassword)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->get($url);

        if ($response->successful()) {
            $responseData = $response->json();

            // Check if payment was successful
            if (isset($responseData['result']) && $responseData['result'] === "SUCCESS") {
                $invoice->payment_status=15;
                $invoice->save();
                PaymentLog::create([
                    'transaction_id' => $transactionId,
                    'status' => "SUCCESS",
                    'amount' => $responseData['order']['amount'],
                    'currency' => $responseData['order']['currency'],
                    'invoice_id'=>$invoice->id,
                    'timestamp' => Carbon::now(),
                ]);

                return response()->json(['status' => 'success', 'message' => 'Payment logged successfully']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Payment verification failed'], 400);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to verify payment'], 500);
        }
    }



    public function success()
    {
        return view('backend.invoice.success');
    }

    public function error()
    {
        return view('backend.invoice.error');
    }
}
