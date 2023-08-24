<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day01 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput();

        $upOneFloorAmount  = substr_count($content, '(');
        $downOneFloorAmount  = substr_count($content, ')');

        return (string) ($upOneFloorAmount - $downOneFloorAmount);
    }

    public function partTwo(): string
    {
        $chars = $this->readInputAsCharacters();
        $position = 0;
        $floor = 0;

        foreach ($chars as $char) {
            $position++;
            match ($char) {
                '(' => $floor++,
                ')' => $floor--,
                default => throw new \InvalidArgumentException(sprintf('No case for %s', $char)),
            };

            if ($floor === -1) {
                return (string) $position;
            }
        }

        throw new \Exception('Unable to find a solution');
    }
}