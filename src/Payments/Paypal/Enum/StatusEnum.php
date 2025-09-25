<?php

namespace App\Payments\Paypal\Enum;

enum StatusEnum: string
{
    case COMPLETED = "COMPLETED";
    case FAILED = "FAILED";
}
