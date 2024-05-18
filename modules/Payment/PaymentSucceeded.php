<?php

namespace Modules\Payment;

use Modules\Order\contracts\OrderDto;
use Modules\User\UserDto;

readonly class PaymentSucceeded
{
    public function __construct(
        public OrderDto $order,
        public UserDto $user
    )
    {

    }
}
