<?php

namespace Modules\Order\Tests\Order\Checkout;

use Modules\Order\Checkout\OrderReceived;
use Modules\Order\contracts\OrderDto;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use function Termwind\render;

class OrderReceivedTest extends TestCase
{
    #[Test]
    public function it_renders_the_mailable()
    {
        $orderDto = new OrderDto(id: 1,
            totalInCents: 99,
            localizedTotal: '$99',
            lines: [],
            url: route('order::order.show', 1));
        $orderReceived = new OrderReceived($orderDto);
        $this->assertIsString($orderReceived->render());
    }
}
