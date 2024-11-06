<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\BackendController;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\Modules\InvoiceService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;
use Stripe\Charge;
use Stripe\Stripe;

class PaymentController extends BackendController
{

    public function __construct(InvoiceService $invoiceService)
    {

        parent::__construct();

        $this->middleware(['permission:invoice_show'])->only('index', 'payment', 'paymentCreate');
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function index(int $id)
    {
        $this->data['invoice'] = Invoice::myinvoice()->findOrfail($id);

        return view('backend.invoice.payments', $this->data);
    }

    /**
     * @param $id
     * @param $paymentMethod
     * @return Application|Factory|View
     */
    public function payment($id, $paymentMethod)
    {
        if (!in_array($paymentMethod, [5, 10, 15])) {
            abort(404);
        }

        $this->data['invoice'] = Invoice::query()
            ->myinvoice()
            ->where('payment_status', '!=', PaymentStatus::FULL_PAID)
            ->findOrfail($id);

        return view('backend.invoice.payment-' . $paymentMethod, $this->data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|RedirectResponse|Redirector
     */
    public function paymentCreate(Request $request, $id)
    {
        $invoice = Invoice::myinvoice()->findOrfail($id);

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
            'due_amount' => 'required|numeric',
            'payment_method' => 'required|numeric',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->amount > $request->due_amount) {
                $validator->errors()->add('amount', 'Your given amount is large than due amount.');
            }
        });

        if ($validator->fails()) {
            return redirect(route('admin.invoice.payment', [$id, $request->get('payment_method')]))
                ->withErrors($validator)->withInput();
        }

        $amount = 0;
        if ($request->payment_method == PaymentMethod::CASH_ON_DELIVERY) {
            $amount = $this->cashPayment($request);
        } else if ($request->payment_method == PaymentMethod::STRIPE) {
            $request->request->add(['note' => $invoice->note]);
            $amount = $this->stripePayment($request);
        } else if ($request->payment_method == PaymentMethod::RAZORPAY) {
            $request->request->add(['invoice_id' => $invoice->id]);
            $amount = $this->razorpayPayment($request);
        }

        if ($amount > 0) {
            $payment = new Payment;
            $payment->user_id = $invoice->user_id;
            $payment->invoice_id = $id;
            $payment->payment_amount = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->create_user_id = auth()->id();
            $payment->save();

            $invoice->payment_status = PaymentStatus::PARTIAL;
            $dueAmount = $invoice->due_amount;
            if ($dueAmount <= 0) {
                $invoice->payment_status = PaymentStatus::FULL_PAID;
            }

            $invoice->save();
        }
        return redirect(route('admin.invoice.payments', $id))->withSuccess('The invoice payment successfully paid.');
    }

    private function cashPayment($request)
    {
        return $request->get('amount', 0);
    }

    public function stripePayment($request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripe = Charge::create([
            "amount" => $request->amount * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => $request->note,
        ]);

        $amount = 0;
        if ($stripe->status == 'succeeded') {
            $amount = $request->amount;
        }

        return $amount;
    }

    /**
     * @param $request
     * @return string|void
     */
    public function razorpayPayment($request)
    {
        Invoice::myinvoice()->findOrfail($request->invoice_id);

        if (blank($request->get('razorpay_payment_id'))) {
            return $request->amount;
        }

        try {
            $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));

            $payment = $api->payment->fetch($request->razorpay_payment_id);

            $payment->capture(array('amount' => $payment['amount']));
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}