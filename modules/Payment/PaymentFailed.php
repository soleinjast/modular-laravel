<?php

namespace Modules\Payment;

use Modules\Order\contracts\OrderDto;
use Modules\User\UserDto;

readonly class PaymentFailed
{
    public function __construct(OrderDto $order, UserDto $user, string $reason)
    {
    }
}
