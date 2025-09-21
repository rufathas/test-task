<?php

namespace App\Doctrine\Type;

use App\Dao\ValueObject\Money;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class MoneyType extends Type
{
    public const string NAME = 'money';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $precision = $column['precision'] ?? 10;
        $scale     = $column['scale'] ?? 2;

        return $platform->getDecimalTypeDeclarationSQL([
            'precision' => $precision,
            'scale'     => $scale,
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Money
    {
        if ($value === null) {
            return null;
        }
        return Money::fromString((string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }
        if ($value instanceof Money) {
            return $value->toString();
        }
        if (is_string($value)) {
            return $value;
        }
        throw new \InvalidArgumentException(sprintf('Invalid Money value %s', get_debug_type($value)));
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
