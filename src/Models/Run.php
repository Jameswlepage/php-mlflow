<?php

namespace PhpMlflow\Models;

class Run
{
    private RunInfo $info;
    private RunData $data;
    private RunInputs $inputs;

    public function __construct(RunInfo $info, RunData $data, RunInputs $inputs)
    {
        $this->info = $info;
        $this->data = $data;
        $this->inputs = $inputs;
    }

    public static function fromArray(array $data): self
    {
        $info = RunInfo::fromArray($data['info']);
        $dataObj = RunData::fromArray($data['data']);
        $inputsObj = RunInputs::fromArray($data['inputs']);
        return new self($info, $dataObj, $inputsObj);
    }

    public function getInfo(): RunInfo
    {
        return $this->info;
    }

    public function getData(): RunData
    {
        return $this->data;
    }

    public function getInputs(): RunInputs
    {
        return $this->inputs;
    }
}

/**
 * RunData represents metrics, params, and tags of a run.
 */
class RunData
{
    /** @var Metric[] */
    private array $metrics;
    /** @var Param[] */
    private array $params;
    /** @var RunTag[] */
    private array $tags;

    public function __construct(array $metrics = [], array $params = [], array $tags = [])
    {
        $this->metrics = $metrics;
        $this->params = $params;
        $this->tags = $tags;
    }

    public static function fromArray(array $data): self
    {
        $metrics = array_map(fn($m) => Metric::fromArray($m), $data['metrics'] ?? []);
        $params = array_map(fn($p) => Param::fromArray($p), $data['params'] ?? []);
        $tags = array_map(fn($t) => RunTag::fromArray($t), $data['tags'] ?? []);
        return new self($metrics, $params, $tags);
    }

    /**
     * @return Metric[]
     */
    public function getMetrics(): array
    {
        return $this->metrics;
    }

    /**
     * @return Param[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return RunTag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}

/**
 * RunInputs represents datasets used as inputs for the run.
 */
class RunInputs
{
    /** @var DatasetInput[] */
    private array $datasetInputs;

    public function __construct(array $datasetInputs = [])
    {
        $this->datasetInputs = $datasetInputs;
    }

    public static function fromArray(array $data): self
    {
        $datasetInputs = array_map(fn($di) => DatasetInput::fromArray($di), $data['dataset_inputs'] ?? []);
        return new self($datasetInputs);
    }

    /**
     * @return DatasetInput[]
     */
    public function getDatasetInputs(): array
    {
        return $this->datasetInputs;
    }
}

/**
 * RunTag represents a key-value pair tag for a run.
 */
class RunTag
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
        return new self($data['key'], $data['value']);
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

/**
 * Dataset represents a reference to a dataset used by a run.
 */
class Dataset
{
    private string $name;
    private string $digest;
    private string $sourceType;
    private string $source;
    private ?string $schema;
    private ?string $profile;

    public function __construct(
        string $name,
        string $digest,
        string $sourceType,
        string $source,
        ?string $schema = null,
        ?string $profile = null
    ) {
        $this->name = $name;
        $this->digest = $digest;
        $this->sourceType = $sourceType;
        $this->source = $source;
        $this->schema = $schema;
        $this->profile = $profile;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['digest'],
            $data['source_type'],
            $data['source'],
            $data['schema'] ?? null,
            $data['profile'] ?? null
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getDigest(): string
    {
        return $this->digest;
    }
    public function getSourceType(): string
    {
        return $this->sourceType;
    }
    public function getSource(): string
    {
        return $this->source;
    }
    public function getSchema(): ?string
    {
        return $this->schema;
    }
    public function getProfile(): ?string
    {
        return $this->profile;
    }
}

/**
 * InputTag represents a tag on a dataset input.
 */
class InputTag
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
        return new self($data['key'], $data['value']);
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

/**
 * DatasetInput represents a dataset used as an input to the run.
 */
class DatasetInput
{
    /** @var InputTag[] */
    private array $tags;
    private Dataset $dataset;

    public function __construct(Dataset $dataset, array $tags = [])
    {
        $this->dataset = $dataset;
        $this->tags = $tags;
    }

    public static function fromArray(array $data): self
    {
        $tags = array_map(fn($t) => InputTag::fromArray($t), $data['tags'] ?? []);
        $dataset = Dataset::fromArray($data['dataset']);
        return new self($dataset, $tags);
    }

    public function getDataset(): Dataset
    {
        return $this->dataset;
    }
    /**
     * @return InputTag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}

/**
 * FileInfo represents artifact file information.
 */
class FileInfo
{
    private string $path;
    private bool $isDir;
    private ?int $fileSize;

    public function __construct(string $path, bool $isDir, ?int $fileSize)
    {
        $this->path = $path;
        $this->isDir = $isDir;
        $this->fileSize = $fileSize;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['path'],
            $data['is_dir'],
            $data['file_size'] ?? null
        );
    }

    public function getPath(): string
    {
        return $this->path;
    }
    public function isDir(): bool
    {
        return $this->isDir;
    }
    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }
}
