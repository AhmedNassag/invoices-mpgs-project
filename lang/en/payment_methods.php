<?php

use App\Enums\PaymentMethod;

return [
    PaymentMethod::CASH_ON_DELIVERY => 'Cash On Delivery',
    PaymentMethod::STRIPE => 'Stripe',
    PaymentMethod::PAYPAL => 'Paypal',
    PaymentMethod::RAZORPAY => 'Razor Pay',
    PaymentMethod::MPGS => 'MPGS',
];
