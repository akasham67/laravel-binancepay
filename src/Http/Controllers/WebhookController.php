<?php

namespace Codeglen\BinancePay\Http\Controllers;

use Codeglen\BinancePay\Events\PaymentPaid;
use Codeglen\BinancePay\Support\WebhookSignature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    public function __invoke(Request $request, WebhookSignature $webhookSignature): JsonResponse
    {
        if (! $webhookSignature->verifyRequest($request)) {
            return response()->json([
                'returnCode' => 'FAIL',
                'returnMessage' => 'Invalid Binance signature',
            ], 403);
        }

        $payload = $request->json()->all();
        $status = (string) data_get($payload, 'data.status', data_get($payload, 'bizStatus', ''));

        if ($status === 'PAID') {
            event(new PaymentPaid($payload));
        }

        return response()->json([
            'returnCode' => 'SUCCESS',
            'returnMessage' => null,
        ]);
    }
}
