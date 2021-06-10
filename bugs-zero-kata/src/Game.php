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
            $this->rockQuestions[] = 'Rock Question ' . $i;
        }
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

    private function howManyPlayers(): int
    {
        return count($this->players);
    }

    public function roll(int $roll): void
    {
        $this->say($this->players[$this->currentPlayer] . ' is the current player');
        $this->say('They have rolled a ' . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            $this->rollForPlayerInPenaltyBox($roll);
        }

        if (!$this->canPlayerAnswerQuestion()) {
            return;
        }

        $this->places[$this->currentPlayer] += $roll;
        if ($this->places[$this->currentPlayer] > 11) {
            $this->places[$this->currentPlayer] -= 12;
        }

        $this->say($this->players[$this->currentPlayer] . '\'s new location is ' . $this->places[$this->currentPlayer]);
        $this->askQuestion();
    }

    private function askQuestion(): void
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

    private function currentCategory(): string
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

    public function checkAnswer(int $answer): bool
    {
        if (!$this->canPlayerAnswerQuestion()) {
            $this->selectNextPlayer();

            return false;
        }

        $winner = false;
        if ($this->isCorrectAnswer($answer)) {
            $this->purses[$this->currentPlayer]++;
            $this->inPenaltyBox[$this->currentPlayer] = false;

            $this->say('Answer was correct!!!!');
            $this->say($this->players[$this->currentPlayer] . ' now has ' . $this->purses[$this->currentPlayer] . ' Gold Coins.');

            $winner = $this->didPlayerWin();
        } else {
            $this->inPenaltyBox[$this->currentPlayer] = true;

            $this->say('Question was incorrectly answered');
            $this->say($this->players[$this->currentPlayer] . ' was sent to the penalty box');
        }

        $this->selectNextPlayer();

        return $winner;
    }

    private function didPlayerWin(): bool
    {
        return $this->purses[$this->currentPlayer] === 6;
    }

    /**
     * @param int $answer
     * @return bool
     */
    private function isCorrectAnswer(int $answer): bool
    {
        return $answer !== 7;
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
        $currentPlayer = $this->players[$this->currentPlayer];

        $this->isGettingOutOfPenaltyBox = $roll % 2 !== 0;
        if ($this->isGettingOutOfPenaltyBox) {
            $this->say($currentPlayer . ' is getting out of the penalty box');
        } else {
            $this->say($currentPlayer . ' is not getting out of the penalty box');
        }
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
    private function canPlayerAnswerQuestion(): bool
    {
        return !$this->inPenaltyBox[$this->currentPlayer] || $this->isGettingOutOfPenaltyBox;
    }
}
