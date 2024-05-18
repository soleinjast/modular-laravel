<?php

namespace Modules\Order\Checkout;

use Modules\Order\contracts\OrderDto;
use Modules\Order\contracts\PendingPayment;
use Modules\User\UserDto;

class OrderStarted
{
    public function __construct(public OrderDto $order, public UserDto $user, public PendingPayment $pendingPayment)
    {


    }
}
