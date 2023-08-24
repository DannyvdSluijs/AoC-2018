<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day05 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInput();

        $letters = range('a', 'z');
        $reactions = [];
        foreach ($letters as $letter) {
            $reactions[] = $letter . strtoupper($letter);
            $reactions[] = strtoupper($letter) . $letter;
        }

        while (true) {
            $before = $content;
            $content = str_replace($reactions, '', $content);

            if ($before === $content) {
                return (string) strlen($content);
            }
        }

        throw new \RuntimeException('Unable to find solution');
    }

    public function partTwo(): string
    {
        $content = $this->readInput();

        $letters = range('a', 'z');
        $reactions = [];
        foreach ($letters as $letter) {
            $reactions[] = $letter . strtoupper($letter);
            $reactions[] = strtoupper($letter) . $letter;
        }

        $best = PHP_INT_MAX;
        foreach ($letters as $letter) {
            $clone = str_replace([$letter, strtoupper($letter)], '', $content);
            while (true) {
                $before = $clone;
                $clone = str_replace($reactions, '', $clone);

                if ($before === $clone) {
                    $best = min($best, strlen($clone));
                    continue 2;
                }
            }

            throw new \RuntimeException('Unable to find solution');
        }

        return (string) $best;
    }
}