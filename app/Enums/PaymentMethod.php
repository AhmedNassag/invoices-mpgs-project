<?php

namespace App\Enums;

enum PaymentMethod
{
    const CASH_ON_DELIVERY = 5;
    const STRIPE = 10;
    const PAYPAL = 15;
    const RAZORPAY = 20;
    const MPGS = 25;
}
