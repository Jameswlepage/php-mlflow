<?php

namespace PhpMlflow\Models;

class ModelVersionTag
{
    private string $key;
    private string $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['key'],
            $data['value']
        );
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
