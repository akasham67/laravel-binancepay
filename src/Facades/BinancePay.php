<?php

namespace Codeglen\BinancePay\Facades;

use Codeglen\BinancePay\DTO\OrderData;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array createOrder(array|OrderData $payload)
 * @method static array queryOrder(string $merchantTradeNo)
 */
class BinancePay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'binancepay';
    }
}
