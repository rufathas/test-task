<?php

namespace App\Doctrine\Type;

use App\Enum\PurchaseStatus;

final class PurchasesStatusType extends PostgresBackedEnumType
{
    public static function getEnumName(): string
    {
        return 'purchases_status';
    }

    public static function getPhpEnumClass(): string
    {
        return PurchaseStatus::class;
    }
}
