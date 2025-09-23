<?php

namespace App\Doctrine\Type;


use App\Enum\Currency;

final class CurrencyType extends PostgresBackedEnumType
{
    public static function getEnumName(): string
    {
        return 'currency';
    }

    public static function getPhpEnumClass(): string
    {
        return Currency::class;
    }
}
