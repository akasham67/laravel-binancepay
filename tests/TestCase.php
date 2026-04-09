<?php

namespace Codeglen\BinancePay\Tests;

use Codeglen\BinancePay\BinancePayServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BinancePayServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('binancepay.secret_key', 'test_secret');
        $app['config']->set('binancepay.certificate_sn', 'test_certificate');
        $app['config']->set('binancepay.webhook.middleware', []);
    }
}
