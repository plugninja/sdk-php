# plug.ninja PHP SDK

Official PHP SDK for [plug.ninja](https://plug.ninja) — Email, Payments, Auth & Brazilian Data APIs with one API key.

## Requirements

- PHP 8.1+
- Composer

## Installation

```bash
composer require plugninja/sdk
```

## Quick Start

```php
<?php

use PlugNinja\Plug;

$plug = new Plug('your-api-key');

// Send an email
$result = $plug->email->send([
    'to' => 'user@example.com',
    'subject' => 'Welcome!',
    'html' => '<h1>Hello from plug.ninja</h1>',
]);

echo $result['messageId'];
```

## Email

```php
// Send email
$plug->email->send([
    'to' => 'user@example.com',
    'subject' => 'Hello',
    'html' => '<h1>Hi</h1>',
]);

// Send OTP
$plug->email->sendOtp('user@example.com');

// Verify OTP
$result = $plug->email->verifyOtp('user@example.com', '123456');
echo $result['token'];
```

## Payments

```php
// Create checkout (Stripe or MercadoPago — configured in dashboard)
$checkout = $plug->payment->checkout([
    'amount' => 2990,       // in cents
    'successUrl' => 'https://yourapp.com/thanks',
    'cancelUrl' => 'https://yourapp.com/cancel',
    'customerEmail' => 'buyer@example.com',
]);

// Redirect user to:
echo $checkout['url'];

// Create subscription
$sub = $plug->payment->subscription([
    'priceId' => 'price_xxx',
    'customerEmail' => 'buyer@example.com',
    'successUrl' => 'https://yourapp.com/thanks',
]);

// Check payment status
$status = $plug->payment->status('payment-id');

// Refund
$plug->payment->refund('payment-id');
```

## Auth (Social Login)

```php
// Get Google OAuth URL
$auth = $plug->auth->google->getAuthUrl([
    'redirectUri' => 'https://yourapp.com/auth/callback',
]);

// Redirect user to $auth['url'], then on callback:
$result = $plug->auth->google->callback([
    'code' => $_GET['code'],
    'redirectUri' => 'https://yourapp.com/auth/callback',
]);

echo $result['user']['email'];
echo $result['tokens']['accessToken'];
```

## Custom Base URL

For local development or self-hosted instances:

```php
$plug = new Plug('your-api-key', 'http://localhost:4000');
```

## Error Handling

```php
use PlugNinja\PlugException;

try {
    $plug->email->send([
        'to' => 'user@example.com',
        'subject' => 'Test',
        'html' => '<p>Hello</p>',
    ]);
} catch (PlugException $e) {
    echo $e->getMessage();    // "From email is required..."
    echo $e->statusCode;      // 400
    var_dump($e->body);        // Full error response body
}
```

## License

MIT
