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

            // The "program" is an inefficient loop with a loop that searches for the divisors for the number from registry one.
            // The search starts when instruction pointer is set at 3.
            if ($instructionPointer === 3) {
                $result = 0;
                $number = $registers[1];
                $max = floor(sqrt($number));
                for ($x = 1; $x <= $max; $x++) {
                    if ($number % $x === 0) {
                        $result += $x;
                        $result += intdiv($number, $x);
                    }
                }

                return (string) $result;
            }

            $instructionPointer++;
        }

        return (string) $registers[0];
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $instructionRegister = (int)substr(array_shift($lines), 4);
        $instructionPointer = 0;
        $registers = [1, 0, 0, 0, 0, 0];
        $instructions = [];

        foreach ($lines as $line) {
            [$opcode, $value1, $value2, $value3] = explode(' ', $line);
            $instructions[] = [$opcode, (int)$value1, (int)$value2, (int)$value3];
        }

        if (false) {
            echo 'PROGRAM:' . PHP_EOL;
            echo str_repeat('=', 25) . PHP_EOL;
            foreach ($registers as $register => $value) {
                printf("\$r%d = %d;\n", $register, $value);
            }
            foreach ($instructions as $index => $instruction) {
                $this->print($index, $instruction);
            }
            echo str_repeat('=', 25) . PHP_EOL;
            echo PHP_EOL;
            echo PHP_EOL;
            echo PHP_EOL;
            echo 'RUN:' . PHP_EOL;
            echo str_repeat('=', 25) . PHP_EOL;
        }

        while(array_key_exists($instructionPointer, $instructions)) {
            $instruction = $instructions[$instructionPointer];
            $registers[$instructionRegister] = $instructionPointer;
            $registers = $this->run($registers, $instruction);
            $instructionPointer = $registers[$instructionRegister];

            // The "program" is an inefficient loop with a loop that searches for the divisors for the number from registry one.
            // The search starts when instruction pointer is set at 3. This is an optimised version of the program
            if ($instructionPointer === 3) {
                $result = 0;
                $number = $registers[1];
                $max = floor(sqrt($number));
                for ($x = 1; $x <= $max; $x++) {
                    if ($number % $x === 0) {
                        $result += $x;
                        $result += intdiv($number, $x);
                    }
                }

                return (string) $result;
            }

            $instructionPointer++;
        }

        return (string) $registers[0];
    }

    private function run($register, $instruction): array
    {
        $register[$instruction[3]] = match ($instruction[0]) {
            'addr' => $register[$instruction[1]] + $register[$instruction[2]],
            'addi' => $register[$instruction[1]] + $instruction[2],
            'mulr' => $register[$instruction[1]] * $register[$instruction[2]],
            'muli' => $register[$instruction[1]] * $instruction[2],
            'setr' => $register[$instruction[1]],
            'seti' => $instruction[1],
            'gtrr' => (($register[$instruction[1]] > $register[$instruction[2]]) ? 1 : 0),
            'eqir' => (($instruction[1] === $register[$instruction[2]]) ? 1 : 0),
            'eqri' => (($register[$instruction[1]] === $instruction[2]) ? 1 : 0),
            'eqrr' => (($register[$instruction[1]] === $register[$instruction[2]]) ? 1 : 0),
            default => throw new \RuntimeException("Missing $instruction[0] implementation"),
        };

        return $register;
    }

    private function print(int $index, array $instruction, array $registers = []): void
    {
        printf('%02d: ', $index);
        if ($instruction[3] === 4) {
            match($instruction[0]) {
                'addr' => printf(
                    'Set pointer to %s + %s + 1',
                    ($instruction[1] === 4 ? $index : ($registers[$instruction[1]] ?? ('$r' . $instruction[1]))),
                    ($instruction[2] === 4 ? $index : ($registers[$instruction[2]] ?? ('$r' . $instruction[2]))),
                ),
                'addi' => printf(
                    "Set pointer to %s + %d + 1",
                    ($instruction[1] === 4 ? $index : ($registers[$instruction[1]] ?? ('$r' . $instruction[1]))),
                    $instruction[2]
                ),
                'mulr' => printf(
                    'Set pointer to %s * %s + 1',
                    ($instruction[1] === 4 ? $index : ($registers[$instruction[1]] ?? ('$r' . $instruction[1]))),
                    ($instruction[2] === 4 ? $index : ($registers[$instruction[2]] ?? ('$r' . $instruction[2]))),
                ),
                'muli' => printf(
                    "Set pointer to %s * %d + 1",
                    ($instruction[1] === 4 ? $index : ($registers[$instruction[1]] ?? ('$r' . $instruction[1]))),
                    $instruction[2]
                ),
                'setr' => printf('Set pointer to %s + 1', ($instruction[1] === 4 ? $index : ($registers[$instruction[1]] ?? ('$r' . $instruction[1])))),
                'seti' => printf('Set pointer to %d', $instruction[1] + 1),
//            'gtrr' => printf("\n"),
//            'eqir' => printf("\n"),
//            'eqri' => printf("\n"),
//                'eqrr' => printf("\$x%d = \$x%d === \$x%d ? 1 :0", $instruction[3], $instruction[1], $instruction[2]),
                default => throw new \RuntimeException("Missing $instruction[0] print implementation"),
            };
            printf(";\n\n");
            return;
        }
        match($instruction[0]) {
            'addr' => printf(
                '$r%d = %s + %s',
                $instruction[3],
                $registers[$instruction[1]] ?? ('$r' . $instruction[1]),
                $registers[$instruction[2]] ?? ('$r' . $instruction[2])
            ),
            'addi' => printf(
                '$r%d = %s + %d',
                $instruction[3],
                $registers[$instruction[1]] ?? ('$r' . $instruction[1]),
                $instruction[2]
            ),
            'mulr' => printf(
                '$r%d = %s * %s',
                $instruction[3],
                $registers[$instruction[1]] ?? ('$r' . $instruction[1]),
                $registers[$instruction[2]] ?? ('$r' . $instruction[2])
            ),
            'muli' => printf(
                '$r%d = %s * %d',
                $instruction[3],
                $registers[$instruction[1]] ?? ('$r' . $instruction[1]),
                $instruction[2]
            ),
            'setr' => printf("\$r%d = \$r%d", $instruction[3], $instruction[1]),
            'seti' => printf("\$r%d = %d", $instruction[3], $instruction[1]),
            'gtrr' => printf("\$r%d = \$r%d > \$r%d ? 1 :0", $instruction[3], $instruction[1], $instruction[2]),
//            'eqir' => printf("\n"),
//            'eqri' => printf("\n"),
            'eqrr' => printf("\$r%d = \$r%d === \$r%d ? 1 :0", $instruction[3], $instruction[1], $instruction[2]),
            default => throw new \RuntimeException("Missing $instruction[0] print implementation"),
        };

        printf(";\n");
    }
}
