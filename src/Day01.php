<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day01 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsLinesOfIntegers();

        return (string) array_sum($content);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLinesOfIntegers();

        $frequency = 0;
        $seen = [$frequency];
        while (true) {
            foreach ($content as $change) {
                $frequency += $change;

                if (in_array($frequency, $seen)) {
                    return (string)$frequency;
                }

                $seen[] = $frequency;
            }
        }
    }
}