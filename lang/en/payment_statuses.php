<?php

use App\Enums\PaymentStatus;

return [
    PaymentStatus::UNPAID   => 'Unpaid',
    PaymentStatus::PARTIAL  => 'Partial',
    PaymentStatus::FULL_PAID => 'Fully Paid',
];
