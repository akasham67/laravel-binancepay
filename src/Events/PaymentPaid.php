<?php

namespace Codeglen\BinancePay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentPaid
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public array $payload)
    {
    }
}
