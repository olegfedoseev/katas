<?php

namespace Archel\TellDontAsk\Repository;

use Archel\TellDontAsk\Domain\Product;
use Archel\TellDontAsk\UseCase\UnknownProductException;

/**
 * Interface ProductCatalog
 * @package Archel\TellDontAsk\Repository
 */
interface ProductCatalog
{
    /**
     * @param string $name
     * @return Product
     *
     * @throws UnknownProductException if no product found
     */
    public function getByName(string $name) : Product;
}
