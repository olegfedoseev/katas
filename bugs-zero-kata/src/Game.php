<?php

namespace Game;

function echoln($string) {
  echo $string . PHP_EOL;
}

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

        echoln($playerName . ' was added');
        echoln('They are player number ' . count($this->players));
        return true;
    }

    public function howManyPlayers(): int
    {
        return count($this->players);
    }

    public function roll(int $roll): void
    {
        echoln($this->players[$this->currentPlayer] . ' is the current player');
        echoln('They have rolled a ' . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($roll % 2 !== 0) {
                $this->isGettingOutOfPenaltyBox = true;

                echoln($this->players[$this->currentPlayer] . ' is getting out of the penalty box');
                $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
                if ($this->places[$this->currentPlayer] > 11) {
                    $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
                }

                echoln($this->players[$this->currentPlayer] . '\'s new location is ' . $this->places[$this->currentPlayer]);
                echoln('The category is ' . $this->currentCategory());
                $this->askQuestion();
            } else {
                echoln($this->players[$this->currentPlayer] . ' is not getting out of the penalty box');
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
            if ($this->places[$this->currentPlayer] > 11) {
                $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
            }

            echoln($this->players[$this->currentPlayer] . '\'s new location is ' . $this->places[$this->currentPlayer]);
            echoln('The category is ' . $this->currentCategory());
            $this->askQuestion();
        }
    }

    public function askQuestion(): void
    {
        if ($this->currentCategory() === 'Pop')
            echoln(array_shift($this->popQuestions));
        if ($this->currentCategory() === 'Science')
            echoln(array_shift($this->scienceQuestions));
        if ($this->currentCategory() === 'Sports')
            echoln(array_shift($this->sportsQuestions));
        if ($this->currentCategory() === 'Rock')
            echoln(array_shift($this->rockQuestions));
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
                echoln('Answer was correct!!!!');
                $this->purses[$this->currentPlayer]++;
                echoln($this->players[$this->currentPlayer]
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

        echoln('Answer was corrent!!!!');
        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer] . ' now has ' .$this->purses[$this->currentPlayer] . ' Gold Coins.');

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }

    public function wrongAnswer(): bool
    {
        echoln('Question was incorrectly answered');
        echoln($this->players[$this->currentPlayer] . ' was sent to the penalty box');
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
}
