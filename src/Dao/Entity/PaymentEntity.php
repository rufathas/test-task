<?php

namespace App\Dao\Entity;

use App\Dao\ValueObject\Money;
use App\Enum\Currency;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payments')]
#[ORM\HasLifecycleCallbacks]
class PaymentEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue('AUTO')]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: PurchaseEntity::class)]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private PurchaseEntity $purchase;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $provider;

    #[ORM\Column(type: Types::STRING, length: 128)]
    private string $providerRef;

    #[ORM\Column(type: 'money', precision: 10, scale: 2)]
    private Money $amount;

    #[ORM\Column(type: 'currency', columnDefinition: 'currency')]
    private Currency $currency;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $errorMessage;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private string $requestBody;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(
        PurchaseEntity $purchase,
        string         $provider,
        string         $providerRef,
        Money          $amount,
        string         $status,
        Currency       $currency,
        ?string        $errorMessage = null,
    )
    {
        $this->purchase = $purchase;
        $this->provider = $provider;
        $this->providerRef = $providerRef;
        $this->amount = $amount;
        $this->status = $status;
        $this->currency = $currency;
        $this->errorMessage = $errorMessage;
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

    public function getPurchase(): PurchaseEntity
    {
        return $this->purchase;
    }

    public function setPurchase(PurchaseEntity $purchase): self
    {
        $this->purchase = $purchase;
        return $this;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    public function getProviderRef(): string
    {
        return $this->providerRef;
    }

    public function setProviderRef(string $ref): self
    {
        $this->providerRef = $ref;
        return $this;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function setAmount(Money $amount): self
    {
        $this->amount = $amount;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $msg): self
    {
        $this->errorMessage = $msg;
        return $this;
    }

    public function getRequestBody(): ?string
    {
        return $this->requestBody;
    }

    public function setRequestBody(?string $body): self
    {
        $this->requestBody = $body;
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
