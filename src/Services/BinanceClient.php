<?php

    namespace Codeglen\BinancePay\Services;

    use Codeglen\BinancePay\Support\Signature;
    use Illuminate\Http\Client\ConnectionException;
    use Illuminate\Http\Client\RequestException;
    use Illuminate\Support\Facades\Http;

    class BinanceClient
    {
        public function __construct(
            protected Signature $signature,
            protected string    $baseUrl,
            protected int       $timeout = 15
        )
        {
        }

        /**
         * Sends a POST request to the specified API endpoint with the given payload.
         *
         * @param string $endpoint The API endpoint to which the POST request is sent.
         * @param array  $payload The data to be sent in the request body.
         * @return array The decoded JSON response from the API.
         * @throws RequestException If the HTTP request fails.
         * @throws ConnectionException
         */
        public function post(string $endpoint, array $payload): array
        {
            $response = Http::withHeaders($this->signature->generate($payload))
                ->timeout($this->timeout)
                ->acceptJson()
                ->asJson()
                ->post(rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/'), $payload)
                ->throw();

            return $response->json() ?? [];
        }

    }
