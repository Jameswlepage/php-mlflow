<?php

namespace PhpMlflow\Services;

use PhpMlflow\Client;
use PhpMlflow\Models\Experiment;
use PhpMlflow\Models\ExperimentTag;

class Experiments
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new experiment.
     */
    public function createExperiment(string $name, ?string $artifactLocation = null, array $tags = []): string
    {
        $payload = ['name' => $name];

        if ($artifactLocation !== null) {
            $payload['artifact_location'] = $artifactLocation;
        }

        if (!empty($tags)) {
            $payload['tags'] = array_map(fn(ExperimentTag $tag) => [
                'key' => $tag->getKey(),
                'value' => $tag->getValue()
            ], $tags);
        }

        $response = $this->client->request('POST', 'experiments/create', [
            'json' => $payload,
        ]);

        return $response['experiment_id'];
    }

    /**
     * Search experiments.
     */
    public function searchExperiments(int $maxResults = 1000, ?string $pageToken = null, ?string $filter = null, array $orderBy = [], ?string $viewType = null): array
    {
        $payload = ['max_results' => $maxResults];

        if ($pageToken !== null) {
            $payload['page_token'] = $pageToken;
        }

        if ($filter !== null) {
            $payload['filter'] = $filter;
        }

        if (!empty($orderBy)) {
            $payload['order_by'] = $orderBy;
        }

        if ($viewType !== null) {
            $payload['view_type'] = $viewType;
        }

        return $this->client->request('POST', 'experiments/search', [
            'json' => $payload,
        ]);
    }

    /**
     * Get experiment by ID.
     */
    public function getExperiment(string $experimentId): Experiment
    {
        $response = $this->client->request('GET', 'experiments/get', [
            'query' => ['experiment_id' => $experimentId],
        ]);

        return Experiment::fromArray($response['experiment']);
    }

    /**
     * Get experiment by name.
     */
    public function getExperimentByName(string $experimentName): Experiment
    {
        $response = $this->client->request('GET', 'experiments/get-by-name', [
            'query' => ['experiment_name' => $experimentName],
        ]);

        return Experiment::fromArray($response['experiment']);
    }

    /**
     * Delete an experiment.
     */
    public function deleteExperiment(string $experimentId): void
    {
        $this->client->request('POST', 'experiments/delete', [
            'json' => ['experiment_id' => $experimentId],
        ]);
    }

    /**
     * Restore a deleted experiment.
     */
    public function restoreExperiment(string $experimentId): void
    {
        $this->client->request('POST', 'experiments/restore', [
            'json' => ['experiment_id' => $experimentId],
        ]);
    }

    /**
     * Update an experiment's metadata.
     */
    public function updateExperiment(string $experimentId, ?string $newName = null): Experiment
    {
        $payload = ['experiment_id' => $experimentId];
        if ($newName !== null) {
            $payload['new_name'] = $newName;
        }

        $response = $this->client->request('POST', 'experiments/update', [
            'json' => $payload,
        ]);

        return Experiment::fromArray($response['experiment']);
    }

    /**
     * Set a tag on an experiment.
     */
    public function setExperimentTag(string $experimentId, string $key, string $value): void
    {
        $this->client->request('POST', 'experiments/set-experiment-tag', [
            'json' => [
                'experiment_id' => $experimentId,
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }
}
