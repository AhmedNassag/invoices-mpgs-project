@extends('_main_layout')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Invoice Overview Report') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a class="text-white"><i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Invoice Overview Report') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.invoice.overview.report.filter') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>{{ __('Customers ') }}</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="0">{{ __('Please Select') }}</option>
                                @if(!blank($users))
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('user_id', $set_user_id) == $user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label to="from_date">{{ __('From Date') }}</label>
                            <input type="text" name="from_date" class="form-control datepicker @error('from_date') is-invalid @enderror" value="{{ old('from_date', $set_from_date) }}"/>
                            @error('from_date')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label to="to_date">{{ __('To Date') }}</label>
                            <input type="text" name="to_date" class="form-control datepicker @error('to_date') is-invalid @enderror" value="{{ old('to_date', $set_to_date) }}"/>
                            @error('to_date')
                                <div class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label>&nbsp;</label><br>
                            <button class="btn btn-primary">{{ __('Get Report ') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($showView)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="m-0">
                        {{ __('Invoice Overview Report') }}
                        <div class="btn-group float-right">
                            <button type="button" class="btn btn-success" onclick="printDiv()">{{ __('Print') }}</button>
                            <a href="{{ route('admin.invoice.overview.report.pdf', [$set_user_id, strtotime($set_from_date), strtotime($set_to_date)]) }}" target="_blank" class="btn btn-warning">{{ __('PDF') }}</a>
                        </div>

                    </h2>
                </div>
                <div class="card-body" id="printDiv">
                     <div class="invoice-heading text-center">
                        @if(green_site_logo())
                            <img class="mx-auto invoice-logo" src="{{ green_site_logo() }}" alt="" />
                        @endif
                        <address class="mb-3">
                            @if(!green_site_logo())
                                <strong>{{ setting('site_name') }}</strong>
                                <br />
                            @endif
                            {{ setting('address') }}
                            <br />
                            {{ setting('phone') }}
                            <br />
                            <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>
                        </address>
                    </div>
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
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPaidAmount    = 0;
                                    $totalDueAmount     = 0;
                                    $totalInvoiceAmount = 0;
                                @endphp
                                @if(!blank($invoices))
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>
                                                @if(auth()->user()->can('invoice_show'))
                                                    <a href="{{ route('admin.invoice.show', $invoice) }}" class="font-weight-bold">
                                                        {{ $invoice->user->name }}
                                                    </a>
                                                @else
                                                    {{ $invoice->user->name }}
                                                @endif
                                            </td>
                                            <td>{{ $invoice->date->format('d M Y') }}</td>
                                            <td>{{ $invoice->paymentstatusname }}</td>
                                            <td>{{ green_number_format($invoice->payment_amount, 2) }}</td>
                                            <td>{{ green_number_format($invoice->due_amount, 2) }}</td>
                                            <td>{{ green_number_format($invoice->total_amount, 2) }}</td>
                                        </tr>
                                        @php
                                            $totalPaidAmount += $invoice->payment_amount;
                                            $totalDueAmount += $invoice->due_amount;
                                            $totalInvoiceAmount += $invoice->total_amount;
                                        @endphp
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="4">
                                        <span class="float-right font-weight-bold">{{ __('TOTAL') }}</span>
                                    </td>
                                    <td>{{ green_number_format($totalPaidAmount) }}</td>
                                    <td>{{ green_number_format($totalDueAmount) }}</td>
                                    <td>{{ green_number_format($totalInvoiceAmount) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- /.container-fluid -->
@endsection

@push('header_css')
    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
@endpush

@push('footer_scripts')
    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script>
        jQuery('.select2').select2();
    </script>
@endpush
