<?php

declare(strict_types=1);

namespace PlugNinja;

class OAuthProvider
{
    public function __construct(
        private readonly HttpClient $http,
        private readonly string $provider,
    ) {}

    /**
     * Get the OAuth redirect URL.
     *
     * @param array{redirectUri?: string, state?: string} $params
     * @return array{url: string}
     */
    public function getAuthUrl(array $params = []): array
    {
        $query = [];
        if (isset($params['redirectUri'])) {
            $query['redirect'] = $params['redirectUri'];
        }
        if (isset($params['state'])) {
            $query['state'] = $params['state'];
        }

        return $this->http->get("/auth/{$this->provider}/url", $query);
    }

    /**
     * Exchange an OAuth code for user info and tokens.
     *
     * @param array{code: string, redirectUri?: string} $params
     * @return array{user: array{id: string, email: string, name: string, picture: string}, tokens: array{accessToken: string, refreshToken?: string, expiresIn: int}}
     */
    public function callback(array $params): array
    {
        return $this->http->post("/auth/{$this->provider}/callback", $params);
    }
}
