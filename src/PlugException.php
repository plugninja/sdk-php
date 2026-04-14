<?php

declare(strict_types=1);

namespace PlugNinja;

use RuntimeException;

class PlugException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly ?array $body = null,
    ) {
        parent::__construct($message, $statusCode);
    }
}
