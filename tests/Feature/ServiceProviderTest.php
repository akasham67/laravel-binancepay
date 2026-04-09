<?php

namespace Codeglen\BinancePay\Tests\Feature;

use Codeglen\BinancePay\BinancePayManager;
use Codeglen\BinancePay\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_manager_is_bound_and_alias_is_registered(): void
    {
        $this->assertInstanceOf(BinancePayManager::class, app(BinancePayManager::class));
        $this->assertInstanceOf(BinancePayManager::class, app('binancepay'));
    }

    public function test_webhook_route_is_registered(): void
    {
        $route = app('router')->getRoutes()->getByName('binancepay.webhook');

        $this->assertNotNull($route);
        $this->assertSame('binancepay/webhook', $route->uri());
    }
}
