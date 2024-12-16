<?php

namespace PhpMlflow\Models;

class RegisteredModel
{
    private string $name;
    private int $creationTimestamp;
    private int $lastUpdatedTimestamp;
    private string $description;
    /** @var ModelVersion[] */
    private array $latestVersions;
    /** @var RegisteredModelTag[] */
    private array $tags;
    private array $aliases;

    public function __construct(
        string $name,
        int $creationTimestamp,
        int $lastUpdatedTimestamp,
        string $description,
        array $latestVersions = [],
        array $tags = [],
        array $aliases = []
    ) {
        $this->name = $name;
        $this->creationTimestamp = $creationTimestamp;
        $this->lastUpdatedTimestamp = $lastUpdatedTimestamp;
        $this->description = $description;
        $this->latestVersions = $latestVersions;
        $this->tags = $tags;
        $this->aliases = $aliases;
    }

    public static function fromArray(array $data): self
    {
        $latestVersions = array_map(function ($mv) {
            return ModelVersion::fromArray($mv);
        }, $data['latest_versions'] ?? []);

        $tags = array_map(function ($tagData) {
            return RegisteredModelTag::fromArray($tagData);
        }, $data['tags'] ?? []);

        $aliases = $data['aliases'] ?? [];

        return new self(
            $data['name'],
            $data['creation_timestamp'],
            $data['last_updated_timestamp'],
            $data['description'],
            $latestVersions,
            $tags,
            $aliases
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreationTimestamp(): int
    {
        return $this->creationTimestamp;
    }

    public function getLastUpdatedTimestamp(): int
    {
        return $this->lastUpdatedTimestamp;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return ModelVersion[]
     */
    public function getLatestVersions(): array
    {
        return $this->latestVersions;
    }

    /**
     * @return RegisteredModelTag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }
}
