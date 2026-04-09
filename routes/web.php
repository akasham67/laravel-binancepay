<?php

use Codeglen\BinancePay\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware((array) config('binancepay.webhook.middleware', ['api']))
    ->post((string) config('binancepay.webhook.path', 'binancepay/webhook'), WebhookController::class)
    ->name('binancepay.webhook');
