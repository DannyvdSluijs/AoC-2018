<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day02 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $content = $this->readInputAsGridOfCharacters();
        $lettersTwoTimes = 0;
        $lettersThreeTimes = 0;

        foreach ($content as $c) {
            $counts = array_count_values($c);
            if (in_array(2, $counts)) {
                $lettersTwoTimes++;
            }
            if (in_array(3, $counts)) {
                $lettersThreeTimes++;
            }
        }

        return (string) ($lettersTwoTimes * $lettersThreeTimes);
    }

    public function partTwo(): string
    {
        $content = $this->readInputAsLines();

        foreach ($content as $a) {
            foreach ($content as $b) {
                if (levenshtein($a, $b) === 1) {
                    $aAsCharArray = str_split($a);
                    $bAsCharArray = str_split($b);
                    $result = '';
                    $length = count($aAsCharArray);

                    for ($x = 0; $x < $length; $x++) {
                        if ($aAsCharArray[$x] === $bAsCharArray[$x]) {
                            $result .= $aAsCharArray[$x];
                        }
                    }

                    return $result;
                }
            }
        }

        throw new \RuntimeException('Unable to find solution');
    }
}