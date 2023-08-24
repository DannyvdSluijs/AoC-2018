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
        self::assertSame($answerPartTwo, $day->partTwo());

    }

    public static  function answersDataProvider(): array
    {
        return [
            'Day 1' => [\Dannyvdsluijs\AdventOfCode2018\Day01::class, '138', '1771'],

        ];
    }

}