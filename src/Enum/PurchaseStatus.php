<?php

namespace App\Enum;

enum PurchaseStatus: string
{
    case CREATED = 'CREATED';
    case PAID = 'PAID';
    case FAILED = 'FAILED';
    case REFUNDED = 'REFUNDED';
}
