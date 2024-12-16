<?php

namespace PhpMlflow\Models;

class Experiment
{
    private string $experimentId;
    private string $name;
    private string $artifactLocation;
    private string $lifecycleStage;
    private int $lastUpdateTime;
    private int $creationTime;
    /** @var ExperimentTag[] */
    private array $tags;

    public function __construct(
        string $experimentId,
        string $name,
        string $artifactLocation,
        string $lifecycleStage,
        int $lastUpdateTime,
        int $creationTime,
        array $tags = []
    ) {
        $this->experimentId = $experimentId;
        $this->name = $name;
        $this->artifactLocation = $artifactLocation;
        $this->lifecycleStage = $lifecycleStage;
        $this->lastUpdateTime = $lastUpdateTime;
        $this->creationTime = $creationTime;
        $this->tags = $tags;
    }

    public static function fromArray(array $data): self
    {
        $tags = array_map(function ($tagData) {
            return ExperimentTag::fromArray($tagData);
        }, $data['tags'] ?? []);

        return new self(
            $data['experiment_id'],
            $data['name'],
            $data['artifact_location'],
            $data['lifecycle_stage'],
            $data['last_update_time'],
            $data['creation_time'],
            $tags
        );
    }

    public function getExperimentId(): string
    {
        return $this->experimentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArtifactLocation(): string
    {
        return $this->artifactLocation;
    }

    public function getLifecycleStage(): string
    {
        return $this->lifecycleStage;
    }

    public function getLastUpdateTime(): int
    {
        return $this->lastUpdateTime;
    }

    public function getCreationTime(): int
    {
        return $this->creationTime;
    }

    /**
     * @return ExperimentTag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
