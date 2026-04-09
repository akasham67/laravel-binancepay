<?php

namespace Codeglen\BinancePay\Enums;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case PAID = 'PAID';
    case EXPIRED = 'EXPIRED';
    case CANCELED = 'CANCELED';

    public static function fromWebhookPayload(array $payload): ?self
    {
        $status = data_get($payload, 'data.status', data_get($payload, 'bizStatus'));

        return is_string($status) ? self::tryFrom($status) : null;
    }
}
