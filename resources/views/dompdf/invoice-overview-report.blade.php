<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice Overview Report</title>
    <link rel="stylesheet" href="{{ asset('dompdf/invoice-overview-report.css') }}">
</head>
<body>
    <div class="main-report">
        <div class="report-header text-center">
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
        <div class="report-content">
            <table class="report-table">
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
                            <td>{{ $invoice->user->name }}</td>
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
                        <span class="text-right font-weight-bold">{{ __('TOTAL') }}</span>
                    </td>
                    <td>{{ green_number_format($totalPaidAmount) }}</td>
                    <td>{{ green_number_format($totalDueAmount) }}</td>
                    <td>{{ green_number_format($totalInvoiceAmount) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
