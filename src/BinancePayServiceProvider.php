<?php

namespace Codeglen\BinancePay;

use Codeglen\BinancePay\Services\BinanceClient;
use Codeglen\BinancePay\Support\Signature;
use Codeglen\BinancePay\Support\WebhookSignature;
use Illuminate\Support\ServiceProvider;

class BinancePayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/binancepay.php', 'binancepay');

        $this->app->singleton(Signature::class, function () {
            return new Signature(
                (string) config('binancepay.secret_key'),
                (string) config('binancepay.certificate_sn')
            );
        });

        $this->app->singleton(WebhookSignature::class, function () {
            return new WebhookSignature((string) config('binancepay.secret_key'));
        });

        $this->app->singleton(BinanceClient::class, function ($app) {
            return new BinanceClient(
                $app->make(Signature::class),
                (string) config('binancepay.base_url'),
                (int) config('binancepay.timeout', 15)
            );
        });

        $this->app->singleton(BinancePayManager::class, function ($app) {
            return new BinancePayManager($app->make(BinanceClient::class), $app->make(WebhookSignature::class));
        });

        $this->app->alias(BinancePayManager::class, 'binancepay');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/binancepay.php' => config_path('binancepay.php'),
        ], 'binancepay-config');

        if (config('binancepay.webhook.enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }
}
