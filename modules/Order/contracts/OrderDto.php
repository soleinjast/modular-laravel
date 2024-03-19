<?php

namespace Modules\Order\contracts;

use Modules\Order\Order;

readonly class OrderDto
{
    public function __construct(
        public int $id,
        public int $totalInCents,
        public string $localizedTotal,
        public array $lines
    )
    {
    }

    public static function fromEloquentModel(Order $order): self
    {
        return new self($order->id, $order->total_in_cents, $order->localizedTotal(), OrderLineDto::fromEloquentCollection($order->lines));
    }

}
