@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Payment List') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.invoice.index') }}" class="text-white">
                    <i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Invoice') }} / {{ __('Payment') }}
                </a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h2 class="mb-0">{{ __('Payment List') }}</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('Payment By') }}</th>
                                    <th>{{ __('Payment Date') }}</th>
                                    <th>{{ __('Payment Method') }}</th>
                                    <th>{{ __('Payment Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!blank($invoice->payments))
                                    @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td>{{ optional($payment->user)->name ?? ''}}</td>
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

            <div class="col-xl-12 col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h2 class="mb-0">{{ __('Invoice Product List') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive invoice-table">
                            <table class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                    <th class="text-right">{{ __('TOTAL') }}</th>
                                </tr>
                                </thead>
                                <tbody>
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

                        @if(!blank($invoice->note))
                            <div class="invoice-notes text-center">
                                <div class="p-3 bg-light rounded">
                                    <span class="font-weight-bold">{{ __('Notes') }}:</span>
                                    {{ $invoice->note }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection