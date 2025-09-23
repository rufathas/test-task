<?php

namespace App\Dao\Entity;

use App\Dao\ValueObject\Money;
use App\Enum\Currency;
use App\Enum\PurchaseStatus;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'purchases')]
#[ORM\HasLifecycleCallbacks]
class PurchaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $taxNumber;

    #[ORM\Column(type: Types::STRING, length: 4)]
    private string $country;

    #[ORM\Column(type: Types::STRING, length: 64, nullable: true)]
    private ?string $couponCode;

    #[ORM\Column(type: 'money', precision: 10, scale: 2)]
    private Money $totalNetAmount;

    #[ORM\Column(type: 'money', precision: 10, scale: 2)]
    private Money $subtotalNet;

    #[ORM\Column(type: 'money', precision: 10, scale: 2)]
    private Money $taxAmount;

    #[ORM\Column(type: 'money', precision: 10, scale: 2)]
    private Money $totalAmount;

    #[ORM\Column(type: 'currency', enumType: Currency::class, columnDefinition: 'currency')]
    private Currency $currency;

    #[ORM\Column(type: 'purchases_status', enumType: PurchaseStatus::class, columnDefinition: 'purchases_status')]
    private PurchaseStatus $status;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        string         $taxNumber,
        string         $country,
        Money         $totalNetAmount,
        Money         $subtotalNet,
        Money         $taxAmount,
        Money         $totalAmount,
        Currency       $currency,
        PurchaseStatus $status,
        ?string        $couponCode = null,
    )
    {
        $this->taxNumber = $taxNumber;
        $this->country = $country;
        $this->couponCode = $couponCode;
        $this->totalNetAmount = $totalNetAmount;
        $this->subtotalNet = $subtotalNet;
        $this->taxAmount = $taxAmount;
        $this->totalAmount = $totalAmount;
        $this->currency = $currency;
        $this->status = $status;
    }

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxNumber(): string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;
        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(?string $couponCode): self
    {
        $this->couponCode = $couponCode;
        return $this;
    }

    public function getTotalNetAmount(): Money
    {
        return $this->totalNetAmount;
    }

    public function setTotalNetAmount(Money $totalNetAmount): self
    {
        $this->totalNetAmount = $totalNetAmount;
        return $this;
    }

    public function getSubtotalNet(): Money
    {
        return $this->subtotalNet;
    }

    public function setSubtotalNet(Money $subtotalNet): self
    {
        $this->subtotalNet = $subtotalNet;
        return $this;
    }

    public function getTaxAmount(): Money
    {
        return $this->taxAmount;
    }

    public function setTaxAmount(Money $taxAmount): self
    {
        $this->taxAmount = $taxAmount;
        return $this;
    }

    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(Money $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function getStatus(): PurchaseStatus
    {
        return $this->status;
    }

    public function setStatus(PurchaseStatus $status): self
    {
        $this->status = $status;
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
}
