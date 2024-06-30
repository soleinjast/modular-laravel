<?php

namespace Modules\Product\Collections;

use Illuminate\Support\Collection;
use Modules\Product\DTOs\CartItem;
use Modules\Product\DTOs\ProductDto;
use Modules\Product\Models\Product;

class CartItemCollection
{
    /**
     * @param Collection<CartItem> $items
     */
    public function __construct(protected Collection $items)
    {

    }

    public static function fromCheckOutData($data): CartItemCollection
    {
        $cartItems = collect($data->input('products'))->map(function (array $productDetails) {
            return new CartItem(ProductDto::fromEloquentModel(Product::query()
                ->find($productDetails['id'])),
                $productDetails['quantity']);
        });
        return new self($cartItems);

    }

    public function totalInCents()
    {
        return $this->items->sum(fn(CartItem $cartItem) => $cartItem->quantity * $cartItem->product->priceInCents);
    }

    /**
     * @return Collection<CartItem>
     */
    public function items(): Collection
    {
        return $this->items;
    }
}
