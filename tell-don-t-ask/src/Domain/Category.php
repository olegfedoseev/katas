<?php

namespace Archel\TellDontAsk\Domain;

/**
 * Class Category
 * @package Archel\TellDontAsk\Domain
 */
class Category
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var float
     */
    private float $taxPercentage;

    public function __construct(string $name, float $taxPercentage)
    {
        $this->name = $name;
        $this->taxPercentage = $taxPercentage;
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
    public function getTaxPercentage() : float
    {
        return $this->taxPercentage;
    }
}
