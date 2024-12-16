<?php

namespace PhpMlflow\Services;

use PhpMlflow\Client;
use PhpMlflow\Models\Run;
use PhpMlflow\Models\Metric;
use PhpMlflow\Models\Param;
use PhpMlflow\Models\RunTag;

class Runs
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createRun(string $experimentId, ?string $runName = null, ?int $startTime = null, array $tags = []): string
    {
        $payload = ['experiment_id' => $experimentId];

        if ($runName !== null) {
            $payload['run_name'] = $runName;
        }

        if ($startTime !== null) {
            $payload['start_time'] = $startTime;
        }

        if (!empty($tags)) {
            $payload['tags'] = array_map(fn(RunTag $tag) => [
                'key' => $tag->getKey(),
                'value' => $tag->getValue(),
            ], $tags);
        }

        $response = $this->client->request('POST', 'runs/create', [
            'json' => $payload,
        ]);

        return $response['run']['info']['run_id'];
    }

    public function deleteRun(string $runId): void
    {
        $this->client->request('POST', 'runs/delete', [
            'json' => ['run_id' => $runId],
        ]);
    }

    public function restoreRun(string $runId): void
    {
        $this->client->request('POST', 'runs/restore', [
            'json' => ['run_id' => $runId],
        ]);
    }

    public function getRun(string $runId): Run
    {
        $response = $this->client->request('GET', 'runs/get', [
            'query' => ['run_id' => $runId],
        ]);

        return Run::fromArray($response['run']);
    }

    public function updateRun(string $runId, array $updates): void
    {
        $payload = array_merge(['run_id' => $runId], $updates);
        $this->client->request('POST', 'runs/update', [
            'json' => $payload,
        ]);
    }

    public function logMetric(string $runId, string $key, float $value, ?int $timestamp = null, ?int $step = null): void
    {
        $payload = [
            'run_id' => $runId,
            'key' => $key,
            'value' => $value,
        ];

        if ($timestamp !== null) {
            $payload['timestamp'] = $timestamp;
        }

        if ($step !== null) {
            $payload['step'] = $step;
        }

        $this->client->request('POST', 'runs/log-metric', [
            'json' => $payload,
        ]);
    }

    public function logParam(string $runId, string $key, string $value): void
    {
        $this->client->request('POST', 'runs/log-parameter', [
            'json' => [
                'run_id' => $runId,
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }

    public function logBatch(string $runId, array $metrics = [], array $params = [], array $tags = []): void
    {
        $payload = ['run_id' => $runId];

        if (!empty($metrics)) {
            $payload['metrics'] = array_map(fn(Metric $m) => [
                'key' => $m->getKey(),
                'value' => $m->getValue(),
                'timestamp' => $m->getTimestamp(),
                'step' => $m->getStep(),
            ], $metrics);
        }

        if (!empty($params)) {
            $payload['params'] = array_map(fn(Param $p) => [
                'key' => $p->getKey(),
                'value' => $p->getValue(),
            ], $params);
        }

        if (!empty($tags)) {
            $payload['tags'] = array_map(fn(RunTag $t) => [
                'key' => $t->getKey(),
                'value' => $t->getValue(),
            ], $tags);
        }

        $this->client->request('POST', 'runs/log-batch', [
            'json' => $payload,
        ]);
    }

    public function setTag(string $runId, string $key, string $value): void
    {
        $this->client->request('POST', 'runs/set-tag', [
            'json' => [
                'run_id' => $runId,
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }

    public function deleteTag(string $runId, string $key): void
    {
        $this->client->request('POST', 'runs/delete-tag', [
            'json' => [
                'run_id' => $runId,
                'key' => $key,
            ],
        ]);
    }

    public function getMetricHistory(string $runId, string $metricKey, ?int $maxResults = null, ?string $pageToken = null): array
    {
        $query = [
            'run_id' => $runId,
            'metric_key' => $metricKey,
        ];

        if ($maxResults !== null) {
            $query['max_results'] = $maxResults;
        }

        if ($pageToken !== null) {
            $query['page_token'] = $pageToken;
        }

        return $this->client->request('GET', 'metrics/get-history', [
            'query' => $query,
        ]);
    }

    public function searchRuns(array $experimentIds, ?string $filter = null, ?string $runViewType = null, int $maxResults = 1000, array $orderBy = [], ?string $pageToken = null): array
    {
        $payload = [
            'experiment_ids' => $experimentIds,
            'max_results' => $maxResults,
        ];

        if ($filter !== null) {
            $payload['filter'] = $filter;
        }

        if ($runViewType !== null) {
            $payload['run_view_type'] = $runViewType;
        }

        if (!empty($orderBy)) {
            $payload['order_by'] = $orderBy;
        }

        if ($pageToken !== null) {
            $payload['page_token'] = $pageToken;
        }

        return $this->client->request('POST', 'runs/search', [
            'json' => $payload,
        ]);
    }

    public function listArtifacts(string $runId, ?string $path = null, ?string $pageToken = null): array
    {
        $query = ['run_id' => $runId];

        if ($path !== null) {
            $query['path'] = $path;
        }

        if ($pageToken !== null) {
            $query['page_token'] = $pageToken;
        }

        return $this->client->request('GET', 'artifacts/list', [
            'query' => $query,
        ]);
    }
}
