<?php

return [
    'base_url' => env('BINANCE_PAY_BASE_URL', 'https://bpay.binanceapi.com'),
    'certificate_sn' => env('BINANCE_PAY_CERTIFICATE_SN', env('BINANCE_PAY_API_KEY')),
    'secret_key' => env('BINANCE_PAY_SECRET_KEY'),
    'timeout' => (int) env('BINANCE_PAY_TIMEOUT', 15),
    'webhook' => [
        'enabled' => env('BINANCE_PAY_WEBHOOK_ENABLED', true),
        'path' => env('BINANCE_PAY_WEBHOOK_PATH', 'binancepay/webhook'),
        'middleware' => ['api'],
    ],
];
