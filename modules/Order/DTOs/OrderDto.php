<?php

namespace Modules\Order\DTOs;

use Illuminate\Database\Eloquent\Collection;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderLine;

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
