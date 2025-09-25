<?php

namespace App\Enum;

enum PurchaseStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
}
