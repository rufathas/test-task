<?php

namespace App\Enum;

enum CouponType: string
{
    case PERCENTAGE = 'percentage';
    case FIXED = 'fixed';
}
