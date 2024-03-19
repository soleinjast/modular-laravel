<?php

namespace Modules\Product\DTOs;

use Modules\Product\Models\Product;

readonly class ProductDto
{
    public function __construct(public int $productId, public int $priceInCents, public int $unitsInStock)
    {

    }

    public static function fromEloquentModel(Product $product) : ProductDto
    {
        return new ProductDto($product->id, $product->price_in_cents, $product->stock);
    }
}
