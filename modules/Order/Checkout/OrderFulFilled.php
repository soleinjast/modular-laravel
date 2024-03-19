<?php

namespace Modules\Order\Checkout;
use Modules\Order\contracts\OrderDto;
use Modules\User\UserDto;

class OrderFulFilled
{

    public function __construct(
        public OrderDto $order,
        public UserDto $user
    )
    {
    }
}
