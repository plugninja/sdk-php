<?php

declare(strict_types=1);

namespace PlugNinja;

class Plug
{
    public readonly Email $email;
    public readonly Payment $payment;
    public readonly Auth $auth;

    private const DEFAULT_BASE_URL = 'https://api.plug.ninja';

    public function __construct(string $apiKey, string $baseUrl = self::DEFAULT_BASE_URL)
    {
        if (empty($apiKey)) {
            throw new PlugException('plug.ninja: apiKey is required');
        }

        $http = new HttpClient($apiKey, rtrim($baseUrl, '/'));

        $this->email = new Email($http);
        $this->payment = new Payment($http);
        $this->auth = new Auth($http);
    }
}
