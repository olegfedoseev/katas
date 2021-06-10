<?php
namespace Game;

use RuntimeException;

class GameRunner
{
    public static function runGame(array $players): void
    {
        $aGame = new Game();

        foreach ($players as $player) {
            $aGame->add($player);
        }

        if (!$aGame->isPlayable()) {
            throw new RuntimeException('Game is not playable');
        }

        do {
            $aGame->roll(rand(0,5) + 1);

            $winner = $aGame->checkAnswer(rand(0,9));
        } while (!$winner);
    }
}



