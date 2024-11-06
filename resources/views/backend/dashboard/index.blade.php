@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fa fa-tachometer-alt"></i> {{ __('global.dashboard') }}</h1>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Invoice (Daily)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ green_number_format($dataInfo['dailyInvoicePaymentAmount']) }}
                                    (<span class="text-danger">{{ green_number_format($dataInfo['dailyInvoiceTotalAmount']) }}</span>)
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Invoice (Weekly)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ green_number_format($dataInfo['weeklyInvoicePaymentAmount']) }}
                                    (<span class="text-danger">{{ green_number_format($dataInfo['weeklyInvoiceTotalAmount']) }}</span>)
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Quotation (Daily)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ green_number_format($dataInfo['dailyQuotationTotalAmount']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Quotation (Weekly)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ green_number_format($dataInfo['weeklyQuotationTotalAmount']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-8">
                <!-- Content Row -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Total Income & Expense(Monthly Wise)') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="canvas"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- Content Row -->
                <div class="card mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Total Income & Expense') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="incomeExpenseChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> {{ __('Income ') }}
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> {{ __('Expense ') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="card mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <div class="col-sm-6 pull-left">
                        <h6 class="m-0 font-weight-bold text-primary">
                            {{ __('Latest Five Invoice') }}
                        </h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                <th>{{ __('Paid Amount') }}</th>
                                <th>{{ __('Due Amount') }}</th>
                                <th>{{ __('Total Amount') }}</th>
                                @can('invoice_show')
                                    <th>{{ __('Action') }}</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @if(!blank($invoices))
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>
                                            @if(auth()->user()->can('invoice_show'))
                                                <a href="{{ route('admin.invoice.show', $invoice) }}" class="font-weight-bold">
                                                    {{ optional($invoice->user)->name }}
                                                </a>
                                            @else
                                                {{ optional($invoice->user)->name }}
                                            @endif
                                        </td>
                                        <td>{{ $invoice->date->format('d M Y') }}</td>
                                        <td>{{ $invoice->paymentstatusname }}</td>
                                        <td>{{ green_number_format($invoice->payments->sum('payment_amount'), 2) }}</td>
                                        <td>{{ green_number_format($invoice->total_amount - $invoice->payments->sum('payment_amount'), 2) }}</td>
                                        <td>{{ green_number_format($invoice->total_amount, 2) }}</td>
                                        @can('invoice_show')
                                            <td>
                                                <a href="{{ route('admin.invoice.show', $invoice) }}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="View"><i class="far fa-check-square"></i></a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('header_css')
    <link href="{{ asset('backend/vendor/chartjs/Chart.min.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/vendor/chartjs/Chart.min.js') }}"></script>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        const dashboard_income          = [<?=$income?>];
        const dashboard_expense         = [<?=$expense?>];
        const totalIncomeAmount         = [<?=$totalIncomeAmount?>];
        const totalExpenseAmount         = [<?=$totalExpenseAmount?>];

    </script>
    <script src="{{ asset('backend/js/dashboard.js') }}"></script>
@endpush
