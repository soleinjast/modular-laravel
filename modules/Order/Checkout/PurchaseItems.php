<?php

namespace Modules\Order\Checkout;
use Illuminate\Database\DatabaseManager;
use Modules\Order\contracts\OrderDto;
use Modules\Order\contracts\PendingPayment;
use Modules\Order\Order;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\Warehouse\ProductStockManager;
use Modules\User\UserDto;
use Illuminate\Contracts\Events\Dispatcher;

class PurchaseItems
{
    public function __construct(protected ProductStockManager $productStockManager,
                                protected CreatePaymentForOrderInterface $createPaymentForOrder,
                                protected DatabaseManager $databaseManager,
                                protected Dispatcher $events)
    {
    }
    public function handle(CartItemCollection $items, PendingPayment $pendingPayment, UserDto $user) : OrderDto
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
            return OrderDto::fromEloquentModel($order);
        });
        $this->events->dispatch(
            new OrderFulFilled(
                order: $order,
                user: $user
            )
        );
        return $order;
    }
}
