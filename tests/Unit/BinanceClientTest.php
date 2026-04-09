<?php

    namespace Codeglen\BinancePay\Tests\Unit;

    use Codeglen\BinancePay\Services\BinanceClient;
    use Codeglen\BinancePay\Tests\TestCase;
    use Illuminate\Http\Client\ConnectionException;
    use Illuminate\Support\Facades\Http;

    class BinanceClientTest extends TestCase
    {


        /**
         * Tests whether the client sends a JSON request with the expected Binance headers
         * when making a POST request to the Binance API endpoint.
         *
         * - Fakes the API response for the Binance endpoint using Laravel's HTTP fake handler.
         * - Sends a POST request to the Binance API with a sample payload.
         * - Asserts that the request includes specific headers required by the Binance API.
         * - Validates that the response status matches the expected outcome.
         * @throws ConnectionException
         */
        public function test_client_posts_json_with_binance_headers(): void
        {
            Http::fake([
                'https://bpay.binanceapi.com/*' => Http::response([
                    'status' => 'SUCCESS',
                ], 200),
            ]);

            $payload = ['merchantTradeNo' => 'TEST-001'];

            $response = app(BinanceClient::class)->post('/binancepay/openapi/v2/order/query', $payload);

            Http::assertSent(function ($request) {
                return $request->url() === 'https://bpay.binanceapi.com/binancepay/openapi/v2/order/query'
                    && $request->hasHeader('BinancePay-Certificate-SN')
                    && $request->hasHeader('BinancePay-Signature')
                    && $request->hasHeader('BinancePay-Nonce')
                    && $request->hasHeader('BinancePay-Timestamp');
            });

            $this->assertSame('SUCCESS', $response['status']);
        }

    }
