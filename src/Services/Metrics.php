<?php

namespace PhpMlflow\Services;

use PhpMlflow\Client;

/**
 * The Metrics service provides convenience methods to interact with metrics endpoints.
 * MLflow metrics are primarily handled via Runs endpoints (log-metric, get-history).
 * This class delegates to Runs internally for simplicity.
 */
class Metrics
{
    private Client $client;
    private Runs $runsService;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->runsService = new Runs($client);
    }

    /**
     * Log a metric for a run.
     */
    public function logMetric(string $runId, string $key, float $value, ?int $timestamp = null, ?int $step = null): void
    {
        $this->runsService->logMetric($runId, $key, $value, $timestamp, $step);
    }

    /**
     * Get metric history for a run.
     */
    public function getMetricHistory(string $runId, string $metricKey, ?int $maxResults = null, ?string $pageToken = null): array
    {
        return $this->runsService->getMetricHistory($runId, $metricKey, $maxResults, $pageToken);
    }
}
