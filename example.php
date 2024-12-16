<?php

require 'vendor/autoload.php';

use PhpMlflow\Client;
use PhpMlflow\Services\Experiments;
use PhpMlflow\Services\Runs;

// Initialize MLflow client
$client = new Client('http://localhost:5000');

// Create an experiment
$experimentsService = new Experiments($client);
$experimentId = $experimentsService->createExperiment('My Experiment');

// Create a run
$runsService = new Runs($client);
$runId = $runsService->createRun($experimentId, 'My First Run');

// Log a parameter
$runsService->logParam($runId, 'learning_rate', '0.01');

// Log a metric
$runsService->logMetric($runId, 'accuracy', 0.95, (int)(microtime(true) * 1000), 1);

// End the run
$runsService->updateRun($runId, ['status' => 'FINISHED', 'end_time' => (int)(microtime(true) * 1000)]);
