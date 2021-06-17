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
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @throws OutOfBoundsException
     */
    public function turnOn(Coordinate $topLeft, Coordinate $bottomRight): void
    {
        $this->checkForOutOfBounds($topLeft, $bottomRight);

        for ($x = $topLeft->getX(); $x <= $bottomRight->getX(); $x++) {
            for ($y = $topLeft->getY(); $y <= $bottomRight->getY(); $y++) {
                $this->light[$x][$y] = true;
            }
        }
    }

    /**
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @throws OutOfBoundsException
     */
    public function turnOff(Coordinate $topLeft, Coordinate $bottomRight): void
    {
        $this->checkForOutOfBounds($topLeft, $bottomRight);

        for ($x = $topLeft->getX(); $x <= $bottomRight->getX(); $x++) {
            for ($y = $topLeft->getY(); $y <= $bottomRight->getY(); $y++) {
                $this->light[$x][$y] = false;
            }
        }
    }

    /**
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @throws OutOfBoundsException
     */
    private function checkForOutOfBounds(Coordinate $topLeft, Coordinate $bottomRight): void
    {
        if ($topLeft->getX() > $this->width || $topLeft->getY() > $this->height) {
            throw new OutOfBoundsException();
        }

        if ($bottomRight->getX() > $this->width || $bottomRight->getY() > $this->height) {
            throw new OutOfBoundsException();
        }
    }
}
