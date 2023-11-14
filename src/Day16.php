<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day16 implements PuzzleDay
{
    use ContentReader;

    private const OPCODES_NAMES = [
        'addr', 'addi',
        'mulr', 'muli',
        'banr', 'bani',
        'borr', 'bori',
        'setr', 'seti',
        'gtir', 'gtri', 'gtrr',
        'eqir', 'eqri', 'eqrr'
    ];

    public function partOne(): string
    {
        [$part1] = explode("\n\n\n", $this->readInput());
        $instructions = explode("\n\n", trim($part1));

        $instructionMatchingThreeOrMoreOpcodes = 0;
        foreach ($instructions as $instruction) {
            [$before, $input, $after] = explode("\n", $instruction);
            $before = array_map(intval(...), explode(', ', substr($before, 9, -1)));
            $input = array_map(intval(...), explode(' ', $input));
            $after = array_map(intval(...), explode(', ', substr($after, 9, -1)));

            $matchingOpcodeNames = 0;
            foreach (self::OPCODES_NAMES as $opcodeName) {
                if ($this->run($opcodeName, $before, $input) === $after) {
                    $matchingOpcodeNames++;
                }
            }

            if ($matchingOpcodeNames >= 3) {
                $instructionMatchingThreeOrMoreOpcodes++;
            }
        }

        return (string) $instructionMatchingThreeOrMoreOpcodes;
    }

    public function partTwo(): string
    {
        [$part1, $part2] = explode("\n\n\n", $this->readInput());
        $instructions = explode("\n\n", trim($part1));

        $analysis = [
            0 => [], 1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => [], 7 => [], 8 => [],
            9 => [], 10 => [], 11 => [], 12 => [], 13 => [], 14 => [], 15 => []
        ];

        foreach ($instructions as $instruction) {
            [$before, $input, $after] = explode("\n", $instruction);
            $before = array_map(intval(...), explode(', ', substr($before, 9, -1)));
            $input = array_map(intval(...), explode(' ', $input));
            $after = array_map(intval(...), explode(', ', substr($after, 9, -1)));

            foreach (self::OPCODES_NAMES as $opcodeName) {
                if (($this->run($opcodeName, $before, $input) === $after) && !in_array($opcodeName, $analysis[$input[0]], true)) {
                    $analysis[$input[0]][] = $opcodeName;
                }
            }
        }

        $opcodeMap = [];
        while (count($analysis) > 0) {
            foreach ($analysis as $opcodeNumber => $possibilities) {
                $possibilities = array_diff($possibilities, array_values($opcodeMap));
                if (count($possibilities) === 1) {
                    $opcodeMap[$opcodeNumber] = array_pop($possibilities);
                    unset($analysis[$opcodeNumber]);
                }
            }
        }

        $registers = [0, 0, 0, 0];
        foreach(explode("\n", trim($part2)) as $input) {
            $input = array_map(intval(...), explode(' ', $input));
            $registers = $this->run($opcodeMap[$input[0]], $registers, $input);
        }

        return (string) $registers[0];
    }

    private function run($opcodeName, $before, $input): array
    {
        $after = $before;

        $after[$input[3]] = match ($opcodeName) {
            'addr' => $before[$input[1]] + $before[$input[2]],
            'addi' => $before[$input[1]] + $input[2],
            'mulr' => $before[$input[1]] * $before[$input[2]],
            'muli' => $before[$input[1]] * $input[2],
            'banr' => ($before[$input[1]] & $before[$input[2]]),
            'bani' => ($before[$input[1]] & $input[2]),
            'borr' => ($before[$input[1]] | $before[$input[2]]),
            'bori' => ($before[$input[1]] | $input[2]),
            'setr' => $before[$input[1]],
            'seti' => $input[1],
            'gtir' => (($input[1] > $before[$input[2]]) ? 1 : 0),
            'gtri' => (($before[$input[1]] > $input[2]) ? 1 : 0),
            'gtrr' => (($before[$input[1]] > $before[$input[2]]) ? 1 : 0),
            'eqir' => (($input[1] === $before[$input[2]]) ? 1 : 0),
            'eqri' => (($before[$input[1]] === $input[2]) ? 1 : 0),
            'eqrr' => (($before[$input[1]] === $before[$input[2]]) ? 1 : 0),
            default => throw new \RuntimeException("Missing $opcodeName implementation"),
        };

        return $after;
    }
}