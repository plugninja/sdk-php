<?php

declare(strict_types=1);

namespace PlugNinja;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpClient
{
    private Client $client;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
    ) {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    /** @return array<string, mixed> */
    public function post(string $path, array $body = []): array
    {
        return $this->request('POST', $path, $body);
    }

    /** @return array<string, mixed> */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, query: $query);
    }

    /** @return array<string, mixed> */
    private function request(string $method, string $path, array $body = [], array $query = []): array
    {
        $options = [];
        if ($method === 'POST' && !empty($body)) {
            $options['json'] = $body;
        }
        if (!empty($query)) {
            $options['query'] = $query;
        }

        try {
            $response = $this->client->request($method, $path, $options);
            $data = json_decode((string) $response->getBody(), true);
            return is_array($data) ? $data : [];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? json_decode((string) $response->getBody(), true) : null;
            $message = is_array($body) && isset($body['error'])
                ? $body['error']
                : "Request failed with status {$statusCode}";

            throw new PlugException($message, $statusCode, is_array($body) ? $body : null);
        }
    }
}
