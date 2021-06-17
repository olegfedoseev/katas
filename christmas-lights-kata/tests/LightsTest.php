<?php

namespace ChristmasTest;

use Christmas\Coordinate;
use Christmas\Lights;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class LightsTest extends TestCase
{
    public function testShouldStartWithLightsOff(): void
    {
        $lights = new Lights(1000, 1000);

        $this->assertEquals(0, $lights->howManyTurnedOn());
    }

    public function testShouldAllowToTurnLightsOn(): void
    {
        $lights = new Lights(1000, 1000);

        $lights->turnOn(new Coordinate(0,0), new Coordinate(2,2));

        $this->assertEquals(9, $lights->howManyTurnedOn());
    }

    /**
     * @testWith [[-1,0], [2,2]]
     *           [[0,-1], [2,2]]
     *           [[0,0], [-1,2]]
     *           [[0,0], [2,-1]]
     *           [[1010,0], [2,2]]
     *           [[0,1010], [2,2]]
     *           [[0,0], [1010,2]]
     *           [[0,0], [2,1010]]
     */
    public function testShouldNotAllowToTurnLightsOutsideOfBounds(array $topLeft, array $bottomRight): void
    {
        $this->expectExceptionObject(new OutOfBoundsException());

        $lights = new Lights(1000, 1000);

        $lights->turnOn(
            new Coordinate($topLeft[0], $topLeft[1]),
            new Coordinate($bottomRight[0], $bottomRight[1])
        );
    }
}
