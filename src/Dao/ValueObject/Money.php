<?php

namespace App\Dao\ValueObject;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

class Money
{
    private const int SCALE = 2;
    private BigDecimal $amount;

    function __construct(BigDecimal $amount)
    {
        $this->amount = $amount->toScale(self::SCALE, RoundingMode::HALF_UP);
    }

    public static function fromString(string $value): self
    {
        return new self(BigDecimal::of($value));
    }

    public static function zero(): self
    {
        return new self(BigDecimal::zero());
    }

    /**
     * @param Money $other
     * @return Money
     */
    public function add(self $other): self
    {
        return new self($this->amount->plus($other->amount));
    }

    /**
     * @param Money $other
     * @return self
     */
    public function subtract(self $other): self
    {
        return new self($this->amount->minus($other->amount));
    }

    /**
     * @param float|int|string $factor
     * @return Money
     */
    public function multiply(float|int|string $factor): self
    {
        return new self($this->amount->multipliedBy(BigDecimal::of($factor)));
    }

    /**
     * @param float|int|string $divisor
     * @return self
     */
    public function divide(float|int|string $divisor): self
    {
        $bd = BigDecimal::of($divisor);
        if ($bd->isEqualTo(BigDecimal::zero())) {
            throw new InvalidArgumentException('Division by zero.');
        }
        return new self($this->amount->dividedBy($bd, self::SCALE, RoundingMode::HALF_UP));
    }

    public function toString(): string
    {
        return $this->amount->toScale(self::SCALE, RoundingMode::HALF_UP)->__toString();
    }

    public function toFloat(): float
    {
        return (float)$this->toString();
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->amount->isEqualTo($other->amount);
    }
}
