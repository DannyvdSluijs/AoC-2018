<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018\Concerns;

trait ContentReader
{
    protected function readFile(string $fileName): string
    {
        $content = file_get_contents($fileName);

        if ($content === false) {
            throw new \InvalidArgumentException(sprintf('Cannot read from file %s', $fileName));

        }

        return trim($content);
    }

    protected function readInputForDay(int $day): string
    {
        return $this->readFile(__DIR__ . sprintf('/../../inputs/day%d.txt', $day));
    }

    protected function readInput(): string
    {
        // Derive the day from the class name (using late static binding)
        return $this->readInputForDay((int) substr(static::class, -2));
    }

    /** @return array<int, string> */
    protected function readInputAsCharacters(): array
    {
        return str_split($this->readInput());
    }

    protected function readInputAsNumber(): int
    {
        return (int) $this->readInput();
    }

    /** @return array<int, string> */
    protected function readInputAsLines(): array
    {
        $content = $this->readInput();
        return explode("\n", $content);
    }

    /** @return array<int, int> */
    public function readInputAsLinesOfIntegers(): array
    {
        return array_map(intval(...), $this->readInputAsLines());
    }

    /** @return array<int, array<int, string>> */
    public function readInputAsGridOfCharacters(): array
    {
        return array_map(str_split(...), $this->readInputAsLines());
    }
}