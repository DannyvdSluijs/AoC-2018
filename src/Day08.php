<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day08 implements PuzzleDay
{
    use ContentReader;

    private array $nodes = [];

    public function partOne(): string
    {
        $content = $this->readInput();
        $numbers = array_map(intval(...), explode(' ', $content));

        $this->readNextNode($numbers);

        $allMetaData = array_map(static fn (object $node) => $node->metaData, $this->nodes);
        $flattened = array_merge(...$allMetaData);

        return (string) array_sum($flattened);
    }

    public function partTwo(): string
    {
        $content = $this->readInput();
        $numbers = array_map(intval(...), explode(' ', $content));

        $this->readNextNode($numbers);

        return (string) $this->nodes[array_key_last($this->nodes)]->value;
    }

    private function readNextNode(array $numbers, $index = 0): int
    {
        $numberOfChildNodes = $numbers[$index];
        $numberOfMetaDataEntries = $numbers[$index + 1];

        $pointer = $index + 2;
        $childNodes = [];
        for ($x = 0; $x < $numberOfChildNodes; $x++) {
            $pointer = $this->readNextNode($numbers, $pointer);
            $childNodes[$x + 1] = $this->nodes[array_key_last($this->nodes)];
        }

        $metaData = array_slice($numbers, $pointer, $numberOfMetaDataEntries);

        if ($numberOfChildNodes > 0) {
            $value = 0;

            foreach ($metaData as $m) {
                if (array_key_exists($m, $childNodes)) {
                    $value += $childNodes[$m]->value;
                }
            }
        } else {
            $value = array_sum($metaData);
        }


        $this->nodes[] = (object) [
            'start' => $index,
            'end' => $pointer + $numberOfMetaDataEntries,
            'numberOfChildNodes' => $numberOfChildNodes,
            'childNodes' => $childNodes,
            'numberOfMetaDataEntries' => $numberOfMetaDataEntries,
            'metaData' => $metaData,
            'value' => $value,
        ];

        return $pointer + $numberOfMetaDataEntries;
    }
}