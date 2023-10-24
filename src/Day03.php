<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day03 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $claims = array_map($this->parseAsClaim(...), $lines);
        $claimsQueue = $claims;
        $overlappingCoords = [];

        foreach ($claims as $c) {
            array_shift($claimsQueue);
            $overlaps = $this->findOverlaps($c, $claimsQueue);

            foreach ($overlaps as $o) {
                $overlappingCoords = [...$overlappingCoords, ...$this->findOverlappingCoords($c, $o)];
            }
        }

        return (string) count(array_unique($overlappingCoords));
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $claims = array_map($this->parseAsClaim(...), $lines);
        $claimsQueue = $claims;
        $overlappingCoords = [];

        foreach ($claims as $c) {
            $overlaps = $this->findOverlaps($c, $claimsQueue);
            if ($overlaps === []) {
                return (string) $c->id;
            }
        }

        throw new \RuntimeException('Unable to find answer');
    }

    public function parseAsClaim(string $line): \stdClass
    {
        $line = str_replace(['#', '@', ':', ',', 'x'], ['', '', '', ' ', ' '], $line);
        $parts = explode(' ', $line);

        return (object) [
            'id' => (int) $parts[0],
            'x' => (int) $parts[2],
            'y' => (int) $parts[3],
            'width' => (int) $parts[4],
            'height' => (int) $parts[5],
        ];
    }

    private function findOverlaps(\stdClass $claim, array $claims): array
    {
        return array_filter($claims, function ($c) use ($claim) {
            if ($c->id === $claim->id) {
                return false;
            }
            if ($c->x > $claim->x + $claim->width - 1) {
                return false;
            }
            if ($c->y > $claim->y -1 + $claim->height) {
                return false;
            }
            if ($c->x + $c->width -1 < $claim->x) {
                return false;
            }
            if ($c->y + $c->height - 1 < $claim->y) {
                return false;
            }
            return true;
        });
    }

    private function findOverlappingCoords($leftClaim, $rightClaim): array
    {
        $coords = [];
        $left = max($leftClaim->x, $rightClaim->x);
        $right = min($leftClaim->x + $leftClaim->width - 1, $rightClaim->x + $rightClaim->width - 1);
        $top = max($leftClaim->y, $rightClaim->y);
        $bottom = min($leftClaim->y + $leftClaim->height - 1, $rightClaim->y + $rightClaim->height - 1);

        for ($x = $left; $x <= $right; $x++) {
            for ($y = $top; $y <= $bottom; $y++) {
                $coords[] = "$x,$y";
            }
        }

        return $coords;
    }
}