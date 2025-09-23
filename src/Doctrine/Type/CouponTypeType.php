<?php

namespace App\Doctrine\Type;

use App\Enum\CouponType;

final class CouponTypeType extends PostgresBackedEnumType
{
    public static function getEnumName(): string
    {
        return 'coupon_type';
    }

    public static function getPhpEnumClass(): string
    {
        return CouponType::class;
    }
}
