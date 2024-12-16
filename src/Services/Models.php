<?php

namespace PhpMlflow\Services;

use PhpMlflow\Client;
use PhpMlflow\Models\RegisteredModel;
use PhpMlflow\Models\ModelVersion;
use PhpMlflow\Models\ModelVersionTag;
use PhpMlflow\Models\RegisteredModelTag;

class Models
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function createRegisteredModel(string $name, ?string $description = null, array $tags = []): RegisteredModel
    {
        $payload = ['name' => $name];

        if ($description !== null) {
            $payload['description'] = $description;
        }

        if (!empty($tags)) {
            $payload['tags'] = array_map(fn(RegisteredModelTag $tag) => [
                'key' => $tag->getKey(),
                'value' => $tag->getValue(),
            ], $tags);
        }

        $response = $this->client->request('POST', 'registered-models/create', [
            'json' => $payload,
        ]);

        return RegisteredModel::fromArray($response['registered_model']);
    }

    public function getRegisteredModel(string $name): RegisteredModel
    {
        $response = $this->client->request('GET', 'registered-models/get', [
            'query' => ['name' => $name],
        ]);

        return RegisteredModel::fromArray($response['registered_model']);
    }

    public function renameRegisteredModel(string $name, string $newName): RegisteredModel
    {
        $payload = [
            'name' => $name,
            'new_name' => $newName,
        ];

        $response = $this->client->request('POST', 'registered-models/rename', [
            'json' => $payload,
        ]);

        return RegisteredModel::fromArray($response['registered_model']);
    }

    public function updateRegisteredModel(string $name, ?string $description = null): RegisteredModel
    {
        $payload = ['name' => $name];
        if ($description !== null) {
            $payload['description'] = $description;
        }

        $response = $this->client->request('PATCH', 'registered-models/update', [
            'json' => $payload,
        ]);

        return RegisteredModel::fromArray($response['registered_model']);
    }

    public function deleteRegisteredModel(string $name): void
    {
        $this->client->request('DELETE', 'registered-models/delete', [
            'query' => ['name' => $name],
        ]);
    }

    public function createModelVersion(string $name, string $source, ?string $runId = null, ?string $runLink = null, ?string $description = null, array $tags = []): ModelVersion
    {
        $payload = [
            'name' => $name,
            'source' => $source,
        ];

        if ($runId !== null) {
            $payload['run_id'] = $runId;
        }

        if ($runLink !== null) {
            $payload['run_link'] = $runLink;
        }

        if ($description !== null) {
            $payload['description'] = $description;
        }

        if (!empty($tags)) {
            $payload['tags'] = array_map(fn(ModelVersionTag $tag) => [
                'key' => $tag->getKey(),
                'value' => $tag->getValue(),
            ], $tags);
        }

        $response = $this->client->request('POST', 'model-versions/create', [
            'json' => $payload,
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function getModelVersion(string $name, string $version): ModelVersion
    {
        $response = $this->client->request('GET', 'model-versions/get', [
            'query' => [
                'name' => $name,
                'version' => $version,
            ],
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function updateModelVersion(string $name, string $version, ?string $description = null): ModelVersion
    {
        $payload = [
            'name' => $name,
            'version' => $version,
        ];

        if ($description !== null) {
            $payload['description'] = $description;
        }

        $response = $this->client->request('PATCH', 'model-versions/update', [
            'json' => $payload,
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function deleteModelVersion(string $name, string $version): void
    {
        $this->client->request('DELETE', 'model-versions/delete', [
            'query' => [
                'name' => $name,
                'version' => $version,
            ],
        ]);
    }

    public function searchRegisteredModels(?string $filter = null, int $maxResults = 100, array $orderBy = [], ?string $pageToken = null): array
    {
        $query = ['max_results' => $maxResults];

        if ($filter !== null) {
            $query['filter'] = $filter;
        }

        if (!empty($orderBy)) {
            $query['order_by'] = $orderBy;
        }

        if ($pageToken !== null) {
            $query['page_token'] = $pageToken;
        }

        return $this->client->request('GET', 'registered-models/search', [
            'query' => $query,
        ]);
    }

    public function setRegisteredModelTag(string $name, string $key, string $value): void
    {
        $this->client->request('POST', 'registered-models/set-tag', [
            'json' => [
                'name' => $name,
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }

    public function setModelVersionTag(string $name, string $version, string $key, string $value): void
    {
        $this->client->request('POST', 'model-versions/set-tag', [
            'json' => [
                'name' => $name,
                'version' => $version,
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }

    public function deleteRegisteredModelTag(string $name, string $key): void
    {
        $this->client->request('DELETE', 'registered-models/delete-tag', [
            'query' => [
                'name' => $name,
                'key' => $key,
            ],
        ]);
    }

    public function deleteModelVersionTag(string $name, string $version, string $key): void
    {
        $this->client->request('DELETE', 'model-versions/delete-tag', [
            'query' => [
                'name' => $name,
                'version' => $version,
                'key' => $key,
            ],
        ]);
    }

    public function setRegisteredModelAlias(string $name, string $alias, string $version): ModelVersion
    {
        $payload = [
            'name' => $name,
            'alias' => $alias,
            'version' => $version,
        ];

        $response = $this->client->request('POST', 'registered-models/alias', [
            'json' => $payload,
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function getModelVersionByAlias(string $name, string $alias): ModelVersion
    {
        $response = $this->client->request('GET', 'registered-models/alias', [
            'query' => [
                'name' => $name,
                'alias' => $alias,
            ],
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function deleteRegisteredModelAlias(string $name, string $alias): void
    {
        $this->client->request('DELETE', 'registered-models/alias', [
            'query' => [
                'name' => $name,
                'alias' => $alias,
            ],
        ]);
    }

    public function transitionModelVersionStage(string $name, string $version, string $stage, bool $archiveExistingVersions): ModelVersion
    {
        $payload = [
            'name' => $name,
            'version' => $version,
            'stage' => $stage,
            'archive_existing_versions' => $archiveExistingVersions,
        ];

        $response = $this->client->request('POST', 'model-versions/transition-stage', [
            'json' => $payload,
        ]);

        return ModelVersion::fromArray($response['model_version']);
    }

    public function getDownloadUriForModelVersionArtifacts(string $name, string $version): string
    {
        $response = $this->client->request('GET', 'model-versions/get-download-uri', [
            'query' => [
                'name' => $name,
                'version' => $version,
            ],
        ]);

        return $response['artifact_uri'];
    }
}
