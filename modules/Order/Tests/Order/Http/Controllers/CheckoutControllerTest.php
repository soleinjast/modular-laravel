<?php

namespace Modules\Order\Tests\Order\Http\Controllers;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\NoReturn;
use Modules\Order\Checkout\OrderReceived;
use Modules\Order\Order;
use Modules\Payment\PayBuddySdk;
use Modules\Payment\PaymentProvider;
use Modules\Product\database\factories\ProductFactory;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;


class CheckoutControllerTest extends TestCase
{
    use DatabaseMigrations;
    #[NoReturn] #[Test]
    public function it_successfully_creates_an_order()
    {
        Mail::fake();
        $user = User::factory()->create();
        $products = ProductFactory::new()->count(2)->create(new Sequence(
            ['name' => 'very expensive air fryer', 'price_in_cents' => 10000, 'stock' => 10],
            ['name' => 'Macbook Pro m3', 'price_in_cents' => 50000, 'stock' => 10]));
        $paymentToken = payBuddySdk::validToken();
        $response = $this->actingAs($user)->post(route('order::checkout', [
            'payment_token' => $paymentToken,
            'products' => [
                ['id' => $products->first()->id, 'quantity' => 2],
                ['id' => $products->last()->id, 'quantity' => 2]
            ]
        ]));
        $order = Order::query()->latest('id')->first();
        $response->assertJson(['order_url' => $order->url()])->assertStatus(201);
        Mail::assertSent(OrderReceived::class, function (OrderReceived $mail) use($user){
            return $mail->hasTo($user->email);
        });
        $this->assertTrue($order->user->is($user));
        $this->assertEquals(120000, $order->total_in_cents);
        $this->assertEquals('paid', $order->status);
        $this->assertCount(2, $order->lines);

        foreach ($products as $product) {
            $orderLine = $order->lines->where('product_id', $product->id)->first();
            $this->assertEquals($product->price_in_cents, $orderLine->product_price_in_cents);
            $this->assertEquals(2, $orderLine->quantity);
        }
        $products = $products->fresh();
        $this->assertEquals(8, $products->first()->stock);
        //payment
        $payment = $order->latestPayment;
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals(PaymentProvider::PayBuddy, $payment->payment_gateway);
        $this->assertEquals(36, strlen($payment->payment_id));
        $this->assertEquals(120000, $payment->total_in_cents);
        $this->assertTrue($payment->user->is($user));
    }
    #[NoReturn] #[Test]
    public function it_fails_with_an_invalid_token() : void
    {
        $user = UserFactory::new()->create();
        $product = ProductFactory::new()->create();
        $payment_token = PayBuddySdk::invalidToken();
        $response = $this->actingAs($user)->postJson(route('order::checkout', [
            'payment_token' => $payment_token,
            'products' => [
                ['id' => $product->id, 'quantity' => 1],
            ]
        ]));
        $response->assertStatus(422)->assertJsonValidationErrors(['payment_token']);
        $this->assertEquals(0, Order::query()->count());

    }
}
