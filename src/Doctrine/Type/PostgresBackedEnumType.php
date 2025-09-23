<?php

namespace App\Doctrine\Type;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

abstract class PostgresBackedEnumType extends Type
{
    abstract public static function getEnumName(): string;

    abstract public static function getPhpEnumClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return static::getEnumName();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BackedEnum
    {
        if ($value === null) return null;
        $enum = static::getPhpEnumClass();
        return $enum::from($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) return null;
        if ($value instanceof BackedEnum) return $value->value;
        if (is_string($value)) return $value;
        throw new InvalidArgumentException('Invalid value for enum ' . static::getEnumName());
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return static::getEnumName();
    }
}
