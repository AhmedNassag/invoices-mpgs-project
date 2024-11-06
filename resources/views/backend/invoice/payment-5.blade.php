@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Invoice') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.invoice.index') }}" class="text-white"><i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Invoice') }}</a> / 
                <a class="text-white">{{ __('Payment') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-4 col-md-4">
                <form method="POST" action="{{ route('admin.invoice.payment.create', $invoice) }}" id="payment-form">
                    @csrf

                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center p-2 mb-0">
                                        <img alt="image" src="{{ $invoice->user->image }}" class="rounded-circle img-profile" />
                                        <ul class="profile-list">
                                          <li>{{ $invoice->user->name }}</li>
                                          <li>{{ $invoice->user->email }}</li>
                                          <li>{{ green_invoice_no($invoice->id) }}</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Payment Amount') }}</label> <span class="text-danger">*</span>
                                        <input type="text" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="{{ green_number_format($invoice->due_amount) }} is your due amount." />
                                        <input type="hidden" name="due_amount" value="{{ $invoice->due_amount }}">
                                        @error('amount')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>{{ __('Payment Method') }}</label> 
                                        <span class="text-danger">*</span>
                                        <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
                                            <option value="5">{{ __('Cash On Delivery') }}</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block" id="updateInvoice">{{ __('Payment') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-xl-8 col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h2 class="mb-0">{{ __('Payment List') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Payment Date') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Payment Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                        @if(!blank($invoice->payments))
                                            @foreach($invoice->payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->user->name ?? ''}}</td>
                                                    <td>{{ $payment->payment_date->format('d M Y h:i A') }}</td>
                                                    <td>{{ $payment->payment }}</td>
                                                    <td>
                                                        {{ green_number_format($payment->payment_amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr class="font-weight-bold">
                                                <td colspan="3">
                                                    {{ __('Total Amount') }}
                                                </td>
                                                <td>
                                                    {{ green_number_format($invoice->payment_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h2 class="mb-0">{{ green_invoice_no($invoice->id) }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Item Name') }}</th>
                                            <th>{{ __('Quantity') }}</th>
                                            <th>{{ __('Unit Price') }}</th>
                                            <th class="text-right">{{ __('TOTAL') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                        @if(!blank($invoice->products))
                                            @foreach($invoice->products as $productItem)
                                                <tr>
                                                    <td>
                                                        <span class="font-weight-bold">{{ optional($productItem->product)->name }}</span>
                                                    </td>
                                                    <td>{{ $productItem->quantity }}</td>
                                                    <td>{{ green_number_format($productItem->unit_price) }}</td>
                                                    <td class="text-right">{{ green_number_format($productItem->subtotal_amount) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td class="text-right font-weight-bold" colspan="3">{{ __('SUBTOTAL') }}</td>
                                            <td class="text-right">
                                                {{ green_number_format($invoice->subtotal_amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right font-weight-bold" colspan="3">{{ __('Discount') }}</td>
                                            <td class="text-right">
                                                {{ green_number_format($invoice->discount_amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right font-weight-bold" colspan="3">{{ __('Delivery Charge') }}</td>
                                            <td class="text-right">
                                                {{ green_number_format($invoice->delivery_charge) }}
                                            </td>
                                        </tr>
                                        @if(!blank($invoice->taxes))
                                            @foreach($invoice->taxes as $tax)
                                                <tr>
                                                    <td class="text-right font-weight-bold" colspan="3">
                                                        {{ optional($tax->tax_rate)->name_with_percent }}
                                                    </td>
                                                    <td class="text-right">{{ green_number_format($tax->amount) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr class="myred">
                                            <td class="text-right font-weight-bold" colspan="3">{{ __('TOTAL TAX') }}</td>
                                            <td class="text-right">
                                                {{ green_number_format($invoice->tax_amount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="grand-total font-weight-bold text-right">{{ __('TOTAL') }}</td>
                                            <td class="grand-total text-highlight text-right">
                                                {{ green_number_format($invoice->total_amount) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
