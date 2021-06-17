<?php

namespace ChristmasTest;

use Christmas\Lights;
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

        $lights->turnOn([0,0], [2,2]);

        $this->assertEquals(9, $lights->howManyTurnedOn());
    }
}
