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
        $this->setValue($topLeft, $bottomRight, fn() => true);
    }

    /**
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @throws OutOfBoundsException
     */
    public function turnOff(Coordinate $topLeft, Coordinate $bottomRight): void
    {
        $this->setValue($topLeft, $bottomRight, fn() => false);
    }

    /**
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @throws OutOfBoundsException
     */
    public function toggle(Coordinate $topLeft, Coordinate $bottomRight): void
    {
        $this->setValue($topLeft, $bottomRight, fn(bool $value) => !$value);
    }

    /**
     * Return current state of lights as ASCII-art
     *
     * @return string
     */
    public function show(): string
    {
        $result = '';
        for ($x = 0; $x <= $this->width; $x++) {
            $result .= PHP_EOL;
            for ($y = 0; $y <= $this->height; $y++) {
                $value = $this->light[$y][$x] ?? false;
                $result .= $value ? '░' : ' ';
            }
        }

        return $result;
    }

    /**
     * @param Coordinate $topLeft
     * @param Coordinate $bottomRight
     * @param callable $newValue callable that receives old value as argument
     */
    private function setValue(Coordinate $topLeft, Coordinate $bottomRight, callable $newValue): void
    {
        $this->checkForOutOfBounds($topLeft, $bottomRight);

        for ($x = $topLeft->getX(); $x <= $bottomRight->getX(); $x++) {
            for ($y = $topLeft->getY(); $y <= $bottomRight->getY(); $y++) {
                $this->light[$x][$y] = $newValue($this->light[$y][$x] ?? false);
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
