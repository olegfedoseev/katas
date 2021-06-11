<?php

namespace Archel\TellDontAsk\Domain;

/**
 * Class Product
 * @package Archel\TellDontAsk\Domain
 */
class Product
{
    private string $name;
    private float $price;
    private Category $category;

    public function __construct(string $name, float $price, Category $category)
    {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice() : float
    {
        return $this->price;
    }

    /**
     * @return Category
     */
    public function getCategory() : Category
    {
        return $this->category;
    }

    public function getUnitaryTax(): float
    {
        return round(
            ($this->getPrice() / 100) * $this->getCategory()->getTaxPercentage(),
            2
        );
    }
}
