@extends('_main_layout')

@section('content')
    
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"> <i class="fas fa-file-invoice-dollar"></i> {{ __('Invoice') }}</h1>

            <span class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <a href="{{ route('admin.invoice.index') }}" class="text-white"><i class="fas fa-file-invoice-dollar fa-sm text-white-50"></i> {{ __('Invoice') }}</a> / 
                <a class="text-white">{{ __('View') }}</a>
            </span>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="main-invoice" id="printDiv">
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
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="invoice-meta mb-3">
                                        <div class="client-info">
                                            <h4 class="title">{{ __('Invoice To') }}:</h4>
                                            <div><span class="font-weight-bold">{{ __('CLIENT') }}:</span> {{ $invoice->user->name }}</div>
                                            <div><span class="font-weight-bold">{{ __('ADDRESS') }}:</span> {{ $invoice->user->address }}</div>
                                            <div><span class="font-weight-bold">{{ __('EMAIL') }}:</span> <a href="mailto:{{ $invoice->user->email }}">{{ $invoice->user->email }}</a></div>
                                            <div><span class="font-weight-bold">{{ __('Phone') }}:</span> <a href="tel:{{ $invoice->user->phone }}">{{ $invoice->user->phone }}</a></div>
                                        </div>
                                        <hr>
                                        <div class="invoice-info">
                                            <h4 class="title">{{ green_invoice_no($invoice->id) }}</h4>
                                            <div><strong>{{ __('Invoice Date') }}:</strong> {{ $invoice->date->format('d M Y') }}</div>
                                            <div><strong>{{ __('Due Date') }}:</strong> {{ $invoice->due_date->format('d M Y') }}</div>
                                            <div><strong>{{ __('Reference No') }}:</strong> {{ $invoice->reference_no }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="table-responsive invoice-table">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Item Name') }}</th>
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
                                </div>
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
                        <div class="invoice-actions text-center mt-3">
                            <ul class="list list-inline mx-auto">
                                <li class="list-inline-item">
                                    <button id="print-invoice-button" type="button" onclick="printDiv()" class="btn btn-primary"> 
                                        <i class="fa fa-print"></i> {{ __('Print Invoice') }}
                                    </button>
                                </li>
                                <li class="list-inline-item">
                                    <button data-toggle="modal" data-target="#shareInvoice" type="button" class="btn btn-danger"> 
                                        <i class="fas fa-envelope"></i> {{ __('Share Invoice') }}
                                    </button>
                                </li>
                                @if(auth()->user()->role_id == 1)
                                    <li class="list-inline-item">
                                        <a href="{{ route('admin.invoice.payment', [$invoice->id, 5]) }}" class="btn btn-primary">
                                            <i class="far fa-credit-card"></i> {{ __('Make A Payment') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

    <div class="modal fade" id="shareInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Share Invoice ') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">{{ __('Email ')}} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="message">{{ __('Message ')}} <span class="text-danger">*</span></label>
                        <textarea name="message" id="message" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                </div>        
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{ __('Close ')}}</button>
                    <button type="button" class="btn btn-primary  btn-sm" id="sendInvoice">{{ __('Send Email') }}</button>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('footer_scripts')
    
    <script>
        function printDiv() {
            var oldPage  = document.body.innerHTML;
            var printDiv = document.getElementById('printDiv').innerHTML;
            document.body.innerHTML = '<html><head><title>'+document.title+'</title></head><body>'+printDiv+'</body></html>';
            window.print();
            document.body.innerHTML = oldPage;
            window.location.reload();
        }

        var invoice_share_url = "{{ route('admin.invoice.share', $invoice->id) }}";
    </script>


    <script src="{{ asset('backend/js/invoice.js') }}"></script>
@endpush