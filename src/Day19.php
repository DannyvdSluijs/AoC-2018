<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day19 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $instructionRegister = (int) substr(array_shift($lines), 4);
        $instructionPointer = 0;
        $registers = [0, 0, 0, 0 , 0, 0];
        $instructions = [];
        foreach ($lines as $line) {
            [$opcode, $value1, $value2, $value3] = explode(' ', $line);
            $instructions[] = [$opcode, (int) $value1, (int) $value2, (int) $value3];
        }

        while(array_key_exists($instructionPointer, $instructions)) {
            $instruction = $instructions[$instructionPointer];
            $registers[$instructionRegister] = $instructionPointer;

            $registers = $this->run($registers, $instruction);
            $instructionPointer = $registers[$instructionRegister];

            $instructionPointer++;
        }

        return (string) $registers[0];
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $instructionRegister = (int) substr(array_shift($lines), 4);
        $instructionPointer = 0;
        $registers = [1, 0, 0, 0 , 0, 0];
        $instructions = [];

        $executed = [];
        foreach ($lines as $line) {
            [$opcode, $value1, $value2, $value3] = explode(' ', $line);
            $instructions[] = [$opcode, (int) $value1, (int) $value2, (int) $value3];
        }

        // Seems to run the follow instruction 1, 2, loop(3, 4, 5, 6, 8, 9, 10, 11)
        while(array_key_exists($instructionPointer, $instructions)) {
            $executed[] = $instructionPointer;
            $instruction = $instructions[$instructionPointer];
            $registers[$instructionRegister] = $instructionPointer;

            printf("ip=%d [%s] %s", $instructionPointer, implode(' ', $registers), implode(' ', $instruction));
            $registers = $this->run($registers, $instruction);
            $instructionPointer = $registers[$instructionRegister];

            printf(" [%s]\n", implode(' ', $registers));
            $instructionPointer++;

            if (count($executed) === 100) {
                break;
            }
        }
        echo json_encode($executed, flags: JSON_PRETTY_PRINT);
        die();
        return (string) $registers[0];
    }

    private function run($register, $instruction): array
    {
        $register[$instruction[3]] = match ($instruction[0]) {
            'addr' => $register[$instruction[1]] + $register[$instruction[2]],
            'addi' => $register[$instruction[1]] + $instruction[2],
            'mulr' => $register[$instruction[1]] * $register[$instruction[2]],
            'muli' => $register[$instruction[1]] * $instruction[2],
//            'banr' => ($register[$instruction[1]] & $register[$instruction[2]]),
//            'bani' => ($register[$instruction[1]] & $instruction[2]),
//            'borr' => ($register[$instruction[1]] | $register[$instruction[2]]),
//            'bori' => ($register[$instruction[1]] | $instruction[2]),
            'setr' => $register[$instruction[1]],
            'seti' => $instruction[1],
//            'gtir' => (($instruction[1] > $register[$instruction[2]]) ? 1 : 0),
//            'gtri' => (($register[$instruction[1]] > $instruction[2]) ? 1 : 0),
            'gtrr' => (($register[$instruction[1]] > $register[$instruction[2]]) ? 1 : 0),
//            'eqir' => (($instruction[1] === $register[$instruction[2]]) ? 1 : 0),
//            'eqri' => (($register[$instruction[1]] === $instruction[2]) ? 1 : 0),
            'eqrr' => (($register[$instruction[1]] === $register[$instruction[2]]) ? 1 : 0),
            default => throw new \RuntimeException("Missing $instruction[0] implementation"),
        };

        return $register;
    }
}