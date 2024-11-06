<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>
    <link rel="stylesheet" href="{{ asset('dompdf/barcode.css') }}">
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
            <div class="barcode-list">
                @for($i = 1; $i <= $set_quantity; $i++)
                    <div class="barcode-item">
                        <p>{{ $selectedProduct->code }} - {{ $i }}</p>
                        <img src="{{ asset('barcode/' . $selectedProduct->code . '.jpg') }}" alt="">
                    </div>
                @endfor
            </div>
        </div>
    </div>
</body>
</html>
