<?php

namespace PhpMlflow\Services;

use PhpMlflow\Client;

/**
 * The Params service provides convenience methods to interact with run parameters.
 * These are handled via run endpoints (log-parameter). We delegate to Runs internally.
 */
class Params
{
    private Client $client;
    private Runs $runsService;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->runsService = new Runs($client);
    }

    /**
     * Log a parameter for a run.
     */
    public function logParam(string $runId, string $key, string $value): void
    {
        $this->runsService->logParam($runId, $key, $value);
    }
}
