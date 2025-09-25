<?php

namespace App\Payments\Stripe\Enum;

enum StatusEnum: string
{
    case CREATED = "CREATED";
    case ERROR = "ERROR";
}
