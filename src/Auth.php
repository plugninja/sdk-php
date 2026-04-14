<?php

declare(strict_types=1);

namespace PlugNinja;

class Auth
{
    public readonly OAuthProvider $google;

    public function __construct(HttpClient $http)
    {
        $this->google = new OAuthProvider($http, 'google');
    }
}
