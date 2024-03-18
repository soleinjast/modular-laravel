<?php

namespace Modules\Product\Tests\Models;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use JetBrains\PhpStorm\NoReturn;
use Modules\Product\Models\Product;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;
    #[NoReturn] public function test_it_creates_an_product()
    {
        $product = Product::factory()->create();
        $this->assertTrue(true);
    }
}
