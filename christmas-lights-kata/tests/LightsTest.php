<?php

namespace ChristmasTest;

use Christmas\Coordinate;
use Christmas\Lights;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class LightsTest extends TestCase
{
    private Lights $lights;

    protected function setUp(): void
    {
        $this->lights = new Lights(1000, 1000);
    }

    public function testShouldStartWithLightsOff(): void
    {
        $this->assertEquals(0, $this->lights->howManyTurnedOn());
    }

    public function testShouldAllowToTurnLightsOn(): void
    {
        $this->lights->turnOn(new Coordinate(0,0), new Coordinate(2,2));

        $this->assertEquals(9, $this->lights->howManyTurnedOn());
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
    public function testShouldNotAllowToTurnLightsOnOutsideOfBounds(array $topLeft, array $bottomRight): void
    {
        $this->expectExceptionObject(new OutOfBoundsException());

        $this->lights->turnOn(
            new Coordinate(...$topLeft),
            new Coordinate(...$bottomRight)
        );
    }

    public function testShouldAllowToTurnLightsOff(): void
    {
        $this->lights->turnOn(new Coordinate(0,0), new Coordinate(2,2));
        $this->lights->turnOff(new Coordinate(1,1), new Coordinate(3,3));

        $this->assertEquals(5, $this->lights->howManyTurnedOn());
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
    public function testShouldNotAllowToTurnLightsOffOutsideOfBounds(array $topLeft, array $bottomRight): void
    {
        $this->expectExceptionObject(new OutOfBoundsException());

        $this->lights->turnOff(
            new Coordinate(...$topLeft),
            new Coordinate(...$bottomRight)
        );
    }

    public function testShouldAllowToToggleLights(): void
    {
        /**
         * 1110
         * 1110
         * 1110
         * 0000
         */
        $this->lights->turnOn(new Coordinate(0,0), new Coordinate(2,2));

        /**
         * After turning off we expect:
         * 1110
         * 1000
         * 1000
         * 0000
         */
        $this->lights->turnOff(new Coordinate(1,1), new Coordinate(3,3));

        /**
         * After toggle we expect:
         * 1110
         * 0111
         * 1000
         * 0000
         */
        $this->lights->toggle(new Coordinate(0,1), new Coordinate(3,1));

        $this->assertEquals(7, $this->lights->howManyTurnedOn());
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
    public function testShouldNotAllowToToggleLightsOffOutsideOfBounds(array $topLeft, array $bottomRight): void
    {
        $this->expectExceptionObject(new OutOfBoundsException());

        $this->lights->toggle(
            new Coordinate(...$topLeft),
            new Coordinate(...$bottomRight)
        );
    }

    public function testShouldFollowSantasInstructions(): void
    {
        // Santa's Instructions
        $this->lights->turnOn(new Coordinate(887,9), new Coordinate(959,629));
        $this->lights->turnOn(new Coordinate(454,398), new Coordinate(844,448));
        $this->lights->turnOn(new Coordinate(539,243), new Coordinate(559,965));
        $this->lights->turnOn(new Coordinate(370,819), new Coordinate(676,868));
        $this->lights->turnOn(new Coordinate(145,40), new Coordinate(370,997));
        $this->lights->turnOn(new Coordinate(301,3), new Coordinate(808,453));
        $this->lights->turnOn(new Coordinate(351,678), new Coordinate(951,908));
        $this->lights->toggle(new Coordinate(720,196), new Coordinate(897,994));
        $this->lights->toggle(new Coordinate(831,394), new Coordinate(904,860));

        // After following the instructions, how many lights are lit?
        $this->assertEquals(554718, $this->lights->howManyTurnedOn());
        // echo $this->lights->show();
    }
}
