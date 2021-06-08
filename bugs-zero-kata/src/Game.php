<?php

namespace Game;

class Game {
    private array $players;
    private array $places;
    private array $purses;
    private array $inPenaltyBox;

    private array $popQuestions;
    private array $scienceQuestions;
    private array $sportsQuestions;
    private array $rockQuestions;

    private int $currentPlayer = 0;
    private bool $isGettingOutOfPenaltyBox;

    public function  __construct()
    {
        $this->players = [];
        $this->places = [0];
        $this->purses  = [0];
        $this->inPenaltyBox  = [0];

        $this->popQuestions = [];
        $this->scienceQuestions = [];
        $this->sportsQuestions = [];
        $this->rockQuestions = [];

        for ($i = 0; $i < 50; $i++) {
            $this->popQuestions[] = 'Pop Question ' . $i;
            $this->scienceQuestions[] = 'Science Question ' . $i;
            $this->sportsQuestions[] = 'Sports Question ' . $i;
            $this->rockQuestions[] = $this->createRockQuestion($i);
        }
    }

    public function createRockQuestion(int $index): string
    {
        return 'Rock Question ' . $index;
    }

    public function isPlayable(): bool
    {
        return ($this->howManyPlayers() >= 2);
    }

    public function add(string $playerName): bool
    {
       $this->players[] = $playerName;
       $this->places[$this->howManyPlayers()] = 0;
       $this->purses[$this->howManyPlayers()] = 0;
       $this->inPenaltyBox[$this->howManyPlayers()] = false;

        $this->say($playerName . ' was added');
        $this->say('They are player number ' . count($this->players));
        return true;
    }

    public function howManyPlayers(): int
    {
        return count($this->players);
    }

    public function roll(int $roll): void
    {
        $this->say($this->players[$this->currentPlayer] . ' is the current player');
        $this->say('They have rolled a ' . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            $this->rollForPlayerInPenaltyBox($roll);
        } else {
            $this->performRoll($roll);
        }
    }

    public function askQuestion(): void
    {
        if ($this->currentCategory() === 'Pop')
            $this->say(array_shift($this->popQuestions));
        if ($this->currentCategory() === 'Science')
            $this->say(array_shift($this->scienceQuestions));
        if ($this->currentCategory() === 'Sports')
            $this->say(array_shift($this->sportsQuestions));
        if ($this->currentCategory() === 'Rock')
            $this->say(array_shift($this->rockQuestions));
    }

    public function currentCategory(): string
    {
        if ($this->places[$this->currentPlayer] === 0) return 'Pop';
        if ($this->places[$this->currentPlayer] === 4) return 'Pop';
        if ($this->places[$this->currentPlayer] === 8) return 'Pop';
        if ($this->places[$this->currentPlayer] === 1) return 'Science';
        if ($this->places[$this->currentPlayer] === 5) return 'Science';
        if ($this->places[$this->currentPlayer] === 9) return 'Science';
        if ($this->places[$this->currentPlayer] === 2) return 'Sports';
        if ($this->places[$this->currentPlayer] === 6) return 'Sports';
        if ($this->places[$this->currentPlayer] === 10) return 'Sports';
        return 'Rock';
    }

    public function wasCorrectlyAnswered(): bool
    {
        if ($this->inPenaltyBox[$this->currentPlayer]){
            if ($this->isGettingOutOfPenaltyBox) {
                $this->say('Answer was correct!!!!');
                $this->purses[$this->currentPlayer]++;
                $this->say($this->players[$this->currentPlayer]
                        . ' now has '
                        .$this->purses[$this->currentPlayer]
                        . ' Gold Coins.');

                $winner = $this->didPlayerWin();
                $this->currentPlayer++;
                if ($this->currentPlayer === count($this->players)) {
                    $this->currentPlayer = 0;
                }

                return $winner;
            }

            $this->currentPlayer++;
            if ($this->currentPlayer === count($this->players)) {
                $this->currentPlayer = 0;
            }

            return true;
        }

        $this->say('Answer was corrent!!!!');
        $this->purses[$this->currentPlayer]++;
        $this->say($this->players[$this->currentPlayer] . ' now has ' .$this->purses[$this->currentPlayer] . ' Gold Coins.');

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }

    public function wrongAnswer(): bool
    {
        $this->say('Question was incorrectly answered');
        $this->say($this->players[$this->currentPlayer] . ' was sent to the penalty box');
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }

        return true;
    }

    public function didPlayerWin(): bool
    {
        return $this->purses[$this->currentPlayer] !== 6;
    }

    private function say(string $string): void
    {
        echo $string . PHP_EOL;
    }

    /**
     * @param int $roll
     */
    private function rollForPlayerInPenaltyBox(int $roll): void
    {
        if ($roll % 2 !== 0) {
            $this->isGettingOutOfPenaltyBox = true;

            $this->say($this->players[$this->currentPlayer] . ' is getting out of the penalty box');
            $this->performRoll($roll);
        } else {
            $this->say($this->players[$this->currentPlayer] . ' is not getting out of the penalty box');
            $this->isGettingOutOfPenaltyBox = false;
        }
    }

    /**
     * @param int $roll
     */
    private function performRoll(int $roll): void
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
        if ($this->places[$this->currentPlayer] > 11) {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
        }

        $this->say($this->players[$this->currentPlayer] . '\'s new location is ' . $this->places[$this->currentPlayer]);
        $this->say('The category is ' . $this->currentCategory());
        $this->askQuestion();
    }
}
