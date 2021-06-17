<?php


namespace ChristmasTest;


use Christmas\Coordinate;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class CoordinateTest extends TestCase
{
    /**
     * @testWith [-1,0]
     *           [0,-1]
     */
    public function testShouldNotAcceptNegativeValuesAsCoordinates(int $x, int $y): void
    {
        $this->expectExceptionObject(new OutOfBoundsException());

        new Coordinate($x, $y);
    }
}
