<?php

namespace Christmas;

class Lights
{
    private array $light;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->light = [];
    }

    public function howManyTurnedOn(): int
    {
        return array_reduce(
            $this->light,
            static fn(int $carry, array $line) => $carry + count(array_filter($line)),
            0
        );
    }

    /**
     * @param array<int> $topLeft
     * @param array<int> $bottomRight
     */
    public function turnOn(array $topLeft, array $bottomRight): void
    {
        for ($x = $topLeft[0]; $x <= $bottomRight[0]; $x++) {
            for ($y = $topLeft[1]; $y <= $bottomRight[1]; $y++) {
                $this->light[$x][$y] = true;
            }
        }
    }
}
