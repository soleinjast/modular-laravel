<?php

namespace Modules\Order\Actions;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\DTOs\PendingPayment;
use Modules\Order\Events\OrderFulFilled;
use Modules\Order\Models\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Product\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\User\UserDto;
use Throwable;

class PurchaseItems
{
    public function __construct(protected ProductStockManager $productStockManager,
                                protected CreatePaymentForOrder $createPaymentForOrder,
                                protected DatabaseManager $databaseManager,
                                protected Dispatcher $events)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $user)
    {
        $order =  $this->databaseManager->transaction(function () use ($items, $user, $pendingPayment) {
                $order = Order::startForUser($user->id);
                $order->addLinesFromCartItems($items);
                $order->fulfill();
            $this->createPaymentForOrder->handle(
                orderId: $order->id,
                userId: $user->id,
                totalInCents: $items->totalInCents(),
                paymentGateway: $pendingPayment->paymentGateway,
                paymentToken: $pendingPayment->paymentToken);
            return $order;
        });
        $this->events->dispatch(
            new OrderFulFilled(
                order: OrderDto::fromEloquentModel($order),
                user: $user
            )
        );
        return $order;
    }
}
