<?php

namespace Codeglen\BinancePay\Tests\Feature;

use Codeglen\BinancePay\Events\PaymentPaid;
use Codeglen\BinancePay\Support\Signature;
use Codeglen\BinancePay\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class WebhookControllerTest extends TestCase
{
    public function test_webhook_accepts_valid_signature_and_dispatches_paid_event(): void
    {
        Event::fake([PaymentPaid::class]);

        $payload = [
            'bizStatus' => 'PAID',
            'data' => ['status' => 'PAID'],
        ];

        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $timestamp = (string) round(microtime(true) * 1000);
        $nonce = Str::random(32);
        $signature = app(Signature::class)->sign($timestamp, $nonce, $body);

        $response = $this->call(
            'POST',
            '/binancepay/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_BinancePay-Timestamp' => $timestamp,
                'HTTP_BinancePay-Nonce' => $nonce,
                'HTTP_BinancePay-Signature' => $signature,
            ],
            $body
        );

        $response->assertStatus(200);
        $response->assertJson(['returnCode' => 'SUCCESS']);
        Event::assertDispatched(PaymentPaid::class);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = ['bizStatus' => 'PAID', 'data' => ['status' => 'PAID']];
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $response = $this->call(
            'POST',
            '/binancepay/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_BinancePay-Timestamp' => (string) round(microtime(true) * 1000),
                'HTTP_BinancePay-Nonce' => Str::random(32),
                'HTTP_BinancePay-Signature' => 'INVALID_SIGNATURE',
            ],
            $body
        );

        $response->assertStatus(403);
        $response->assertJson(['returnCode' => 'FAIL']);
    }
}
