<?php

namespace Codeglen\BinancePay;

use Codeglen\BinancePay\Actions\CreateOrder;
use Codeglen\BinancePay\Actions\QueryOrder;
use Codeglen\BinancePay\DTO\OrderData;
use Codeglen\BinancePay\Services\BinanceClient;
use Codeglen\BinancePay\Support\WebhookSignature;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class BinancePayManager
{
    public function __construct(
        protected BinanceClient    $client,
        protected WebhookSignature $webhookSignature
    ) {
    }

    /**
     * Handles the creation of an order using the provided payload.
     *
     * @param array|OrderData $payload The data required to create the order. Can be an array or an instance of OrderData.
     * @return array The response from the order creation process.
     * @throws ConnectionException|RequestException
     */
    public function createOrder(array|OrderData $payload): array
    {
        if ($payload instanceof OrderData) {
            $payload = $payload->toArray();
        }

        return (new CreateOrder($this->client))->execute($payload);
    }

    /**
     * Execute a query for a specific merchant trade number and return the result as an array.
     *
     * @param string $merchantTradeNo The unique merchant trade number to query.
     * @return array The queried order details.
     * @throws ConnectionException|RequestException
     */
    public function queryOrder(string $merchantTradeNo): array
    {
        return (new QueryOrder($this->client))->execute($merchantTradeNo);
    }

    public function verifyWebhookRequest(Request $request): bool
    {
        return $this->webhookSignature->verifyRequest($request);
    }

}
