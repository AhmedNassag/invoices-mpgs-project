<!DOCTYPE html>
<html>
<head>
    <title>Invoice Pay Link</title>
</head>
<body>
    <h1>Hello,</h1>
    <p>Your Invoice Details:</p>
    <p><strong>No:</strong> {{ $invoice['id'] }}</p>
    <p><strong>Serial:</strong> {{ $invoice['uuid'] }}</p>
    <p>You can click here to go to pay</p><a href="{{ route('checkout',$invoice['uuid']) }}"></a>
    <p>Thank you!</p>
</body>
</html>
