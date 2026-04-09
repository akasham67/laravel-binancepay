<?php

    namespace Codeglen\BinancePay\Actions;

    use Codeglen\BinancePay\Services\BinanceClient;
    use Illuminate\Http\Client\ConnectionException;
    use Illuminate\Http\Client\RequestException;

    class CreateOrder
    {
        public function __construct(protected BinanceClient $client)
        {
        }


        /**
         * Executes a request to the Binance Pay API to create or manage an order.
         *
         * @param array $payload The data payload to be sent with the API request.
         * @return array The response received from the API.
         * @throws ConnectionException
         * @throws RequestException
         */
        public function execute(array $payload): array
        {
            return $this->client->post('/binancepay/openapi/v2/order', $payload);
        }

    }
