<?php

namespace App\Config\Resource;

abstract class JsonResource
{
    protected mixed $data;
    protected array $with = [];

    final public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    public static function make(mixed $data): static
    {
        return new static($data);
    }

    abstract public function toArray(array $context = []): array;

    public function additional(array $data): static
    {
        $this->with = $data + $this->with;
        return $this;
    }

    protected function when(bool $cond, mixed $value, mixed $default = null): mixed
    {
        return $cond ? (is_callable($value) ? $value() : $value) : $default;
    }

    protected function mergeWhen(bool $cond, array $value): array
    {
        return $cond ? $value : [];
    }

    protected function whenNotNull(mixed $value, mixed $default = null): mixed
    {
        return $value !== null ? $value : $default;
    }

    public function jsonSerialize(): array
    {
        return ['data' => $this->toArray()] + $this->with;
    }

    public function resolve(array $context = []): array
    {
        return ['data' => $this->toArray($context)] + $this->with;
    }
}
