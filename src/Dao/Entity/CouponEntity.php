<?php

namespace App\Dao\Entity;

use App\Enum\CouponType;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'coupons')]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'uniq_coupons_code_is_active', columns: ['code', 'is_active'])]
class CouponEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    private string $code;

    #[ORM\Column(type: 'coupon_type', columnDefinition: 'coupon_type')]
    private CouponType $type;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $value;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $validFrom = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $validTo = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(string $code, CouponType $type, string $value)
    {
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
    }

    # Lifecycle callbacks
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $now = new DateTimeImmutable();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    # Getters/Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getType(): CouponType
    {
        return $this->type;
    }

    public function setType(CouponType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): BigDecimal
    {
        return BigDecimal::of($this->value);
    }

    public function setValue(BigDecimal $value): self
    {
        $this->value = $value->__toString();
        return $this;
    }

    public function getValidFrom(): ?DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function setValidFrom(?DateTimeImmutable $from): self
    {
        $this->validFrom = $from;
        return $this;
    }

    public function getValidTo(): ?DateTimeImmutable
    {
        return $this->validTo;
    }

    public function setValidTo(?DateTimeImmutable $to): self
    {
        $this->validTo = $to;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $active): self
    {
        $this->isActive = $active;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isPercent(): bool
    {
        return $this->type === CouponType::PERCENTAGE;
    }

    public function isCurrentlyValid(?DateTimeImmutable $date = null): bool
    {
        if (!$this->isActive) {
            return false;
        }
        $date = $date ?? new DateTimeImmutable();
        if ($this->validFrom !== null && $date < $this->validFrom) {
            return false;
        }
        if ($this->validTo !== null && $date > $this->validTo) {
            return false;
        }
        return true;
    }

    public function getPercentFraction(): ?string
    {
        if (!$this->isPercent()) {
            return null;
        }

        return BigDecimal::of($this->value)
        ->dividedBy('100', 6, RoundingMode::HALF_UP)
        ->__toString();
    }
}
