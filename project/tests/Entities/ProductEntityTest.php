<?php

namespace tests\Entities;

use App\Entities\ProductEntity;
use PHPUnit\Framework\TestCase;

class ProductEntityTest extends TestCase
{
    public function testCanCreateProductEntity()
    {
        $product = new ProductEntity([
            'name' => 'Teclado Mecánico',
            'brand' => 'Logitech',
            'price' => 99.99
        ]);

        $this->assertEquals('Teclado Mecánico', $product->name);
        $this->assertEquals('Logitech', $product->brand);
        $this->assertEquals(99.99, $product->price);
    }

    public function testCanToggleActive()
    {
        $product = new ProductEntity();
        $this->assertTrue($product->active);

        $product->toggleActive();
        $this->assertFalse($product->active);
    }

    public function testToJson()
    {
        $product = new ProductEntity([
            'name' => 'Mouse Gamer',
            'brand' => 'Razer',
            'price' => 49.99
        ]);

        $json = $product->toJson();
        $this->assertJson($json);

        $decoded = json_decode($json, true);
        $this->assertEquals('Mouse Gamer', $decoded['name']);
        $this->assertEquals('Razer', $decoded['brand']);
        $this->assertEquals(49.99, $decoded['price']);
    }
}
