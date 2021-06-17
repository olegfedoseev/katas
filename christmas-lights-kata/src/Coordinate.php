<?php

namespace Christmas;

use OutOfBoundsException;

class Coordinate
{
    private int $x;
    private int $y;

    public function __construct(int $x, int $y)
    {
        if ($x < 0 || $y < 0) {
            throw new OutOfBoundsException();
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }
}
