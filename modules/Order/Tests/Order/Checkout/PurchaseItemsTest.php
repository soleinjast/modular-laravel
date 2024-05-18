<?php
declare(strict_types=1);

namespace Modules\Order\Tests\Order\Checkout;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Mockery\MockInterface;
use Modules\Order\Checkout\PurchaseItems;
use Modules\Order\contracts\PendingPayment;
use Modules\Order\Order;
use Modules\Payment\Actions\CreatePaymentForOrder;
use Modules\Payment\Actions\CreatePaymentForOrderInMemory;
use Modules\Payment\Actions\CreatePaymentForOrderInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\InMemoryGateway;
use Modules\Payment\Models\Payment;
use Modules\Payment\PayBuddySdk;
use Modules\Product\Collections\CartItemCollection;
use Modules\Product\database\factories\ProductFactory;
use Modules\User\UserDto;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;


class PurchaseItemsTest extends TestCase
{
    use DatabaseMigrations;

    #[NoReturn] #[Test]
    public function it_creates_an_order() : void
    {
        Mail::fake();
        Event::fake();
        // make a user
        $user = UserFactory::new()->create();
        // make a product
        $product = ProductFactory::new()->create([
            'stock' => 10,
            'price_in_cents' => 100
        ]);
        // make an array of single product
        $products = ['id' => $product->id, 'quantity' => 2];

        $request = Request::create('/test', 'POST', [
            'products' => [$products]
        ]);
        $createPayment = new CreatePaymentForOrderInMemory();
        $this->app->instance(CreatePaymentForOrderInterface::class, $createPayment);

        $cartItemsCollection = CartItemCollection::fromCheckOutData($request);
        $pendingPayment = new PendingPayment(new InMemoryGateway(), (string)Str::uuid());
        $userDto = UserDto::fromEloquentModel($user);

        $purchaseItems = app(PurchaseItems::class);

        $order = $purchaseItems->handle($cartItemsCollection, $pendingPayment, $userDto);
        $orderLine = $order->lines[0];
        $this->assertEquals($product->price_in_cents * 2, $order->totalInCents);
        $this->assertCount(1, $order->lines);
        $this->assertEquals($product->id, $orderLine->productId);
        $this->assertEquals(2, $orderLine->quantity);
    }
}
