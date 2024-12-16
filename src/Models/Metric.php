<?php

namespace PhpMlflow\Models;

class Metric
{
    private string $key;
    private float $value;
    private int $timestamp;
    private ?int $step;

    public function __construct(string $key, float $value, int $timestamp, ?int $step = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->timestamp = $timestamp;
        $this->step = $step;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['key'],
            (float) $data['value'],
            $data['timestamp'],
            $data['step'] ?? null
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }
}
