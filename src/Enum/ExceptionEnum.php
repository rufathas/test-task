<?php

namespace App\Enum;

enum ExceptionEnum: string
{
    case PRODUCT_NOT_FOUND = 'Product not found';
    case COUPON_CODE_NOT_FOUND = 'Coupon code not found';
    case COUNTRY_RATE_NOT_FOUND = 'Country rate not found';
    case INTERNAL_SERVER_ERROR = 'Internal Server Error';
}
