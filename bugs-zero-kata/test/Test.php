<?php

use Game\Game;
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

    public function testShouldLeavePrisonAfterCorrectAnswer(): void
    {
        ob_start();
        $aGame = new Game();

        $aGame->add("Player 1");
        $aGame->add("Player 2");

        // Player 1
        $aGame->roll(1);
        $aGame->checkAnswer(7); // wrong answer -> go to prison

        // Player 2
        $aGame->roll(1);
        $aGame->checkAnswer(7); // wrong answer -> go to prison

        // Player 1
        $aGame->roll(3); // getting out of prison
        $aGame->checkAnswer(1);

        // Player 2
        $aGame->roll(2); //  stay in prison
        $aGame->checkAnswer(7);

        // Player 1
        $aGame->roll(2); // not getting out of prison, but should be not in prison
        $aGame->checkAnswer(1);

        // Player 2
        $aGame->roll(2);
        $aGame->checkAnswer(1);

        $actual = ob_get_clean();
        $expected = file_get_contents('approved-penalty.txt');
        $this->assertEquals($expected, $actual);
    }
}
