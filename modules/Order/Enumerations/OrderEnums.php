<?php

namespace Modules\Order\Enumerations;

enum OrderEnums
{
    public const COMPLETED = 'completed';
    public const PENDING = 'pending';
    public const PAYMENT_FAILED = 'payment_failed';
    public const PAID = 'paid';

    public static function getStatusEnums(): array
    {
        return [
            self::COMPLETED,
            self::PENDING,
            self::PAYMENT_FAILED,
            self::PAID
        ];
    }
}
