<?php

namespace Codeglen\BinancePay\Support;

use Illuminate\Http\Request;

class WebhookSignature
{
    public function __construct(protected string $secretKey)
    {
    }

    public function verifyRequest(Request $request): bool
    {
        $signature = (string) $request->header('BinancePay-Signature', '');
        $timestamp = (string) $request->header('BinancePay-Timestamp', '');
        $nonce = (string) $request->header('BinancePay-Nonce', '');
        $body = (string) $request->getContent();

        if ($signature === '' || $timestamp === '' || $nonce === '' || $body === '') {
            return false;
        }

        return $this->verify($body, $signature, $timestamp, $nonce);
    }

    public function verify(string $body, string $signature, string $timestamp, string $nonce): bool
    {
        $payloadToSign = $timestamp . "\n" . $nonce . "\n" . $body . "\n";
        $expected = strtoupper(hash_hmac('sha512', $payloadToSign, $this->secretKey));

        return hash_equals($expected, strtoupper($signature));
    }
}
