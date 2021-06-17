<?php

namespace Christmas;

use OutOfBoundsException;

class Lights
{
    private array $light;
    private int $width;
    private int $height;

    /**
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

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
        if ($topLeft[0] < 0 || $topLeft[1] < 0 || $bottomRight[0] < 0 || $bottomRight[1] < 0) {
            throw new OutOfBoundsException();
        }

        if ($topLeft[0] > $this->width || $topLeft[1] > $this->height || $bottomRight[0] > $this->width || $bottomRight[1] > $this->height) {
            throw new OutOfBoundsException();
        }

        for ($x = $topLeft[0]; $x <= $bottomRight[0]; $x++) {
            for ($y = $topLeft[1]; $y <= $bottomRight[1]; $y++) {
                $this->light[$x][$y] = true;
            }
        }
    }
}
