<?php

    namespace Codeglen\BinancePay\Actions;

    use Codeglen\BinancePay\Services\BinanceClient;
    use Illuminate\Http\Client\ConnectionException;
    use Illuminate\Http\Client\RequestException;

    class QueryOrder
    {
        public function __construct(protected BinanceClient $client)
        {
        }

        /**
         * Executes a POST request to query an order using the provided merchant trade number.
         *
         * @param string $merchantTradeNo The unique identifier for the merchant trade.
         *
         * @return array The response array from the API.
         * @throws ConnectionException|RequestException
         */
        public function execute(string $merchantTradeNo): array
        {
            return $this->client->post('/binancepay/openapi/v2/order/query', [
                'merchantTradeNo' => $merchantTradeNo,
            ]);
        }

    }
