<?php

namespace App\Dao\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tax_rates')]
#[ORM\Index(name: 'idx_tax_rates_country', columns: ['country'])]
#[ORM\UniqueConstraint(name: 'uniq_country_code_rate', columns: ['country', 'mask'])]
#[ORM\HasLifecycleCallbacks]
class TaxRateEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue('AUTO')]
    #[ORM\Column(type: Types::BIGINT)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 4)]
    private string $country;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 4)]
    private string $rate;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $mask;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updatedAt;

    public function __construct(string $country, string $rate, string $mask)
    {
        $this->country = $country;
        $this->rate = $rate;
        $this->mask = $mask;
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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function getMask(): string
    {
        return $this->mask;
    }

    public function setMask(string $mask): void
    {
        $this->mask = $mask;
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
