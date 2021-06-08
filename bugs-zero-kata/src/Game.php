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
        $category = $this->currentCategory();
        $this->say('The category is ' . $category);

        switch ($category) {
            case 'Pop':
                $question = array_shift($this->popQuestions);
                break;
            case 'Science':
                $question = array_shift($this->scienceQuestions);
                break;
            case 'Sports':
                $question = array_shift($this->sportsQuestions);
                break;
            case 'Rock':
                $question = array_shift($this->rockQuestions);
                break;
        }

        $this->say($question);
    }

    public function currentCategory(): string
    {
        switch ($this->places[$this->currentPlayer]) {
            case 0:
            case 4:
            case 8:
                return 'Pop';
            case 1:
            case 5:
            case 9:
                return 'Science';
            case 2:
            case 6:
                return 'Sports';
            default:
                return 'Rock';
        }
    }

    public function wasCorrectlyAnswered(): bool
    {
        if (!$this->inPenaltyBox[$this->currentPlayer]) {
            return $this->correctAnswer();
        }

        if ($this->isGettingOutOfPenaltyBox) {
            return $this->correctAnswer();
        }

        $this->selectNextPlayer();

        return true;
    }

    public function wrongAnswer(): bool
    {
        $this->say('Question was incorrectly answered');
        $this->say($this->players[$this->currentPlayer] . ' was sent to the penalty box');
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->selectNextPlayer();

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
        $this->askQuestion();
    }

    private function selectNextPlayer(): void
    {
        $this->currentPlayer++;
        if ($this->currentPlayer === count($this->players)) {
            $this->currentPlayer = 0;
        }
    }

    /**
     * @return bool
     */
    private function correctAnswer(): bool
    {
        $this->say('Answer was correct!!!!');
        $this->purses[$this->currentPlayer]++;
        $this->say($this->players[$this->currentPlayer] . ' now has ' . $this->purses[$this->currentPlayer] . ' Gold Coins.');

        $winner = $this->didPlayerWin();
        $this->selectNextPlayer();
        return $winner;
    }
}
