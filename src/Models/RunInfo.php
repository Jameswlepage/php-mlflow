<?php

namespace PhpMlflow\Models;

class RunInfo
{
    private string $runId;
    private ?string $runUuid;
    private ?string $runName;
    private string $experimentId;
    private ?string $userId;
    private string $status;
    private int $startTime;
    private ?int $endTime;
    private string $artifactUri;
    private string $lifecycleStage;

    public function __construct(
        string $runId,
        ?string $runUuid,
        ?string $runName,
        string $experimentId,
        ?string $userId,
        string $status,
        int $startTime,
        ?int $endTime,
        string $artifactUri,
        string $lifecycleStage
    ) {
        $this->runId = $runId;
        $this->runUuid = $runUuid;
        $this->runName = $runName;
        $this->experimentId = $experimentId;
        $this->userId = $userId;
        $this->status = $status;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->artifactUri = $artifactUri;
        $this->lifecycleStage = $lifecycleStage;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['run_id'],
            $data['run_uuid'] ?? null,
            $data['run_name'] ?? null,
            $data['experiment_id'],
            $data['user_id'] ?? null,
            $data['status'],
            $data['start_time'],
            $data['end_time'] ?? null,
            $data['artifact_uri'],
            $data['lifecycle_stage']
        );
    }

    public function getRunId(): string
    {
        return $this->runId;
    }

    public function getRunUuid(): ?string
    {
        return $this->runUuid;
    }

    public function getRunName(): ?string
    {
        return $this->runName;
    }

    public function getExperimentId(): string
    {
        return $this->experimentId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartTime(): int
    {
        return $this->startTime;
    }

    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    public function getArtifactUri(): string
    {
        return $this->artifactUri;
    }

    public function getLifecycleStage(): string
    {
        return $this->lifecycleStage;
    }
}
