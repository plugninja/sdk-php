<?php

declare(strict_types=1);

namespace PlugNinja;

class HttpClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl,
    ) {}

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
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'x-api-key: ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($body)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            }
        }

        $response = curl_exec($ch);
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new PlugException("cURL error: {$curlError}", 0);
        }

        $data = json_decode($response, true);

        if ($statusCode >= 400) {
            $message = is_array($data) && isset($data['error'])
                ? $data['error']
                : "Request failed with status {$statusCode}";
            throw new PlugException($message, $statusCode, is_array($data) ? $data : null);
        }

        return is_array($data) ? $data : [];
    }
}
