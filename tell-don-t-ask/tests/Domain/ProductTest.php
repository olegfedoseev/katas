<?php

namespace Archel\TellDontAskTest\Domain;

use Archel\TellDontAsk\Domain\Category;
use Archel\TellDontAsk\Domain\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testWillReturnCorrectUnitaryTaxForProduct(): void
    {
        $category = new Category("test category", 10.0);
        $product  = new Product("test product", 321, $category);

        $this->assertEquals(32.1, $product->getUnitaryTax());
    }
}
