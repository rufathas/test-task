<?php

namespace App\Dao\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'purchase_idempotency_keys')]
#[ORM\UniqueConstraint(name: 'uniq_pik_purchase_id', columns: ['purchase_id', 'key'])]
#[ORM\Index(name: 'idx_pik_created_at', columns: ['created_at'])]
class IdempotencyKeyEntity
{
    #[ORM\Id]
    #[ORM\Column(name: 'key', type: Types::STRING, length: 128)]
    private string $key;

    #[ORM\ManyToOne(targetEntity: PurchaseEntity::class)]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private PurchaseEntity $purchase;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    public function __construct(string $key, PurchaseEntity $purchase)
    {
        $this->key = $key;
        $this->purchase = $purchase;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getKey(): string
    {
        return $this->key;
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
