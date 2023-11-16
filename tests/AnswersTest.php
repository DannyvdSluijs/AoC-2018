<?php

declare(strict_types=1);

use Dannyvdsluijs\AdventOfCode2018\PuzzleDay;
use PHPUnit\Framework\TestCase;

class AnswersTest extends TestCase
{
    /**
     * @dataProvider answersDataProvider
     */
    public function testAnswerIsCorrect(string $dayClassName, string $answerPartOne, string $answerPartTwo): void
    {
        /** @var PuzzleDay $day */
        $day = new $dayClassName();

        self::assertSame($answerPartOne, $day->partOne());

        if ($answerPartTwo !== '') {
            self::assertSame($answerPartTwo, $day->partTwo());
        }

    }

    public static  function answersDataProvider(): array
    {
        return [
            'Day 1' => [\Dannyvdsluijs\AdventOfCode2018\Day01::class, '406', '312'],
            'Day 3' => [\Dannyvdsluijs\AdventOfCode2018\Day03::class, '109143', '506'],
            'Day 7' => [\Dannyvdsluijs\AdventOfCode2018\Day07::class, 'GRTAHKLQVYWXMUBCZPIJFEDNSO', '1115'],
            'Day 8' => [\Dannyvdsluijs\AdventOfCode2018\Day08::class, '45618', '22306'],
            'Day 16' => [\Dannyvdsluijs\AdventOfCode2018\Day16::class, '529', '573'],
        ];
    }

}