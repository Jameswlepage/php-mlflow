<?php

namespace PhpMlflow;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use PhpMlflow\Exceptions\ApiException;

class Client
{
    private GuzzleClient $httpClient;
    private string $baseUri;

    public function __construct(string $baseUri, array $config = [])
    {
        $this->baseUri = rtrim($baseUri, '/') . '/api/2.0/mlflow/';
        $this->httpClient = new GuzzleClient(array_merge([
            'base_uri' => $this->baseUri,
            'timeout'  => 10.0,
        ], $config));
    }

    public function getHttpClient(): GuzzleClient
    {
        return $this->httpClient;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Sends a request to the MLflow API.
     *
     * @param string $method HTTP method (GET, POST, PATCH, DELETE)
     * @param string $uri API endpoint URI
     * @param array  $options Request options (query, json, etc.)
     * @return array Decoded JSON response
     * @throws ApiException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $uri, $options);
            $body = $response->getBody()->getContents();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException("Invalid JSON response from MLflow server.");
            }
            if (isset($decoded['error'])) {
                throw new ApiException($decoded['error'], $response->getStatusCode());
            }

            return $decoded;
        } catch (RequestException $e) {
            $message = $e->getMessage();
            if ($e->hasResponse()) {
                $message = $e->getResponse()->getBody()->getContents();
            }
            throw new ApiException($message, $e->getCode(), $e);
        }
    }
}
