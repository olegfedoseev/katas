<?php

use Game\GameRunner;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testLockDown(): void
    {
        mt_srand(123455);
        ob_start();

        GameRunner::runGame(["Chet","Pat","Sue"]);

        $actual = ob_get_clean();

        $expected = file_get_contents('approved.txt');
        $this->assertEquals($expected, $actual);
    }

    public function testShouldNotAllowLessThanTwoPlayers(): void
    {
        $this->expectExceptionObject(new RuntimeException('Game is not playable'));

        GameRunner::runGame(["Chet"]);
    }
}
