<?php

namespace App\Enum;

enum CouponType: string
{
    case Percent = 'percent';
    case Fixed   = 'fixed';
}
