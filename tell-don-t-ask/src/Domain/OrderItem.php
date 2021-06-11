<?php

namespace Archel\TellDontAsk\Domain;

/**
 * Class OrderItem
 * @package Archel\TellDontAsk\Domain
 */
class OrderItem
{
    private Product $product;
    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->setProduct($product);
        $this->setQuantity($quantity);
    }

    /**
     * @return Product
     */
    public function getProduct() : Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product) : void
    {
        $this->product = $product;
    }

    /**
     * @return int
     */
    public function getQuantity() : int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity) : void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getTaxedAmount() : float
    {
        $unitaryTaxedAmount = round($this->product->getPrice() + $this->product->getUnitaryTax(), 2);
        return round($unitaryTaxedAmount * $this->quantity, 2);
    }

    /**
     * @return float
     */
    public function getTax() : float
    {
        return round($this->product->getUnitaryTax() * $this->quantity, 2);
    }
}
