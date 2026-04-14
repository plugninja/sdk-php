<?php

declare(strict_types=1);

namespace PlugNinja;

class Email
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * Send an email.
     *
     * @param array{to: string, subject: string, html: string} $params
     * @return array{success: bool, messageId: string}
     */
    public function send(array $params): array
    {
        return $this->http->post('/email/send', $params);
    }

    /**
     * Send a one-time password to an email address.
     *
     * @param string $email
     * @return array{success: bool, message: string}
     */
    public function sendOtp(string $email): array
    {
        return $this->http->post('/email/send-otp', ['email' => $email]);
    }

    /**
     * Verify a one-time password.
     *
     * @param string $email
     * @param string $code
     * @return array{success: bool, token: string, email: string}
     */
    public function verifyOtp(string $email, string $code): array
    {
        return $this->http->post('/email/verify-otp', [
            'email' => $email,
            'code' => $code,
        ]);
    }
}
