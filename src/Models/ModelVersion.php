<?php

namespace PhpMlflow\Models;

class ModelVersion
{
    private string $name;
    private string $version;
    private int $creationTimestamp;
    private int $lastUpdatedTimestamp;
    private string $userId;
    private string $currentStage;
    private ?string $description;
    private string $source;
    private string $runId;
    private string $status;
    private ?string $statusMessage;
    /** @var ModelVersionTag[] */
    private array $tags;
    private ?string $runLink;
    private array $aliases;

    public function __construct(
        string $name,
        string $version,
        int $creationTimestamp,
        int $lastUpdatedTimestamp,
        string $userId,
        string $currentStage,
        ?string $description,
        string $source,
        string $runId,
        string $status,
        ?string $statusMessage,
        array $tags = [],
        ?string $runLink = null,
        array $aliases = []
    ) {
        $this->name = $name;
        $this->version = $version;
        $this->creationTimestamp = $creationTimestamp;
        $this->lastUpdatedTimestamp = $lastUpdatedTimestamp;
        $this->userId = $userId;
        $this->currentStage = $currentStage;
        $this->description = $description;
        $this->source = $source;
        $this->runId = $runId;
        $this->status = $status;
        $this->statusMessage = $statusMessage;
        $this->tags = $tags;
        $this->runLink = $runLink;
        $this->aliases = $aliases;
    }

    public static function fromArray(array $data): self
    {
        $tags = array_map(function ($tagData) {
            return ModelVersionTag::fromArray($tagData);
        }, $data['tags'] ?? []);

        $aliases = $data['aliases'] ?? [];

        return new self(
            $data['name'],
            $data['version'],
            $data['creation_timestamp'],
            $data['last_updated_timestamp'],
            $data['user_id'],
            $data['current_stage'],
            $data['description'] ?? null,
            $data['source'],
            $data['run_id'],
            $data['status'],
            $data['status_message'] ?? null,
            $tags,
            $data['run_link'] ?? null,
            $aliases
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCreationTimestamp(): int
    {
        return $this->creationTimestamp;
    }

    public function getLastUpdatedTimestamp(): int
    {
        return $this->lastUpdatedTimestamp;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getCurrentStage(): string
    {
        return $this->currentStage;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getRunId(): string
    {
        return $this->runId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusMessage(): ?string
    {
        return $this->statusMessage;
    }

    /**
     * @return ModelVersionTag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    public function getRunLink(): ?string
    {
        return $this->runLink;
    }

    public function getAliases(): array
    {
        return $this->aliases;
    }
}
