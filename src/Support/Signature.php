<?php

namespace Codeglen\BinancePay\Support;

use Illuminate\Support\Str;

class Signature
{
    public function __construct(
        protected string $secretKey,
        protected string $certificateSn
    ) {
    }

    public function generate(array $payload): array
    {
        $timestamp = (int) round(microtime(true) * 1000);
        $nonce = Str::random(32);
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $signature = $this->sign($timestamp, $nonce, $body ?: '{}');

        return [
            'Content-Type' => 'application/json',
            'BinancePay-Timestamp' => (string) $timestamp,
            'BinancePay-Nonce' => $nonce,
            'BinancePay-Certificate-SN' => $this->certificateSn,
            'BinancePay-Signature' => $signature,
        ];
    }

    public function sign(string|int $timestamp, string $nonce, string $body): string
    {
        $payloadToSign = $timestamp . "\n" . $nonce . "\n" . $body . "\n";

        return strtoupper(hash_hmac('sha512', $payloadToSign, $this->secretKey));
    }
}
