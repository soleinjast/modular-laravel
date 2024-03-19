<?php

namespace Modules\Order\Tests\Order\Models;

use Modules\Order\Order;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_it_creates_an_order()
    {
        $order = new Order();
        $this->assertTrue(true);
    }
}
