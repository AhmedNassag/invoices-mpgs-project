<?php

namespace App\Enums;

enum PaymentStatus
{
    const UNPAID = 5;
    const PARTIAL = 10;
    const FULL_PAID = 15;
}