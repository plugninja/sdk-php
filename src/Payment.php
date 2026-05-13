<?php

declare(strict_types=1);

namespace PlugNinja;

class Payment
{
    public function __construct(private readonly HttpClient $http) {}

    /**
     * Create a checkout session.
     *
     * For Mercado Pago Bricks (transparent checkout), the response includes
     * `preferenceId` and `publicKey` to initialize the Brick on your frontend.
     *
     * @param array{
     *   amount: int,
     *   currency?: string,
     *   provider?: 'stripe'|'mercadopago',
     *   successUrl?: string,
     *   cancelUrl?: string,
     *   customerEmail?: string,
     *   title?: string,
     *   metadata?: array<string, string>,
     * } $params
     * @return array{
     *   success: bool,
     *   id: string,
     *   url: string|null,
     *   provider: string,
     *   preferenceId?: string,
     *   publicKey?: string,
     *   mode?: string,
     * }
     */
    public function checkout(array $params): array
    {
        return $this->http->post('/payment/checkout', $params);
    }

    /**
     * Create a subscription.
     *
     * @param array{
     *   priceId: string,
     *   customerEmail: string,
     *   successUrl: string,
     *   cancelUrl?: string,
     *   metadata?: array<string, string>,
     * } $params
     * @return array{success: bool, id: string, url: string}
     */
    public function subscription(array $params): array
    {
        return $this->http->post('/payment/subscription', $params);
    }

    /**
     * Get payment status.
     *
     * @param string $paymentId
     * @return array{id: string, externalId: ?string, amount: int, currency: string, status: string, provider: string, type: string, customerEmail: ?string, createdAt: string}
     */
    public function status(string $paymentId): array
    {
        return $this->http->get("/payment/{$paymentId}");
    }

    /**
     * Refund a payment.
     *
     * @param string $paymentId
     * @return array{success: bool, refundId: string, status: string}
     */
    public function refund(string $paymentId): array
    {
        return $this->http->post("/payment/refund/{$paymentId}");
    }
}
