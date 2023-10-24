<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day04 implements PuzzleDay
{
    use ContentReader;

    public function partOne(): string
    {
        $lines = $this->readInputAsLines();
        $parsed = array_map($this->parseLine(...), $lines);
        usort($parsed, static fn(\stdClass $l, \stdClass $r) => $l->datetime <=> $r->datetime);

        [$totalSleep, $sleepMinutes] = $this->computeSleepTimes($parsed);

        $mostTotalSleep = max($totalSleep);
        $totalSleepMap = array_flip($totalSleep);
        $guardWithMostSleep = $totalSleepMap[$mostTotalSleep];

        [$favoriteMinute] = $this->findFavouriteMinuteAndFrequency($sleepMinutes[$guardWithMostSleep]);

        return (string) ($guardWithMostSleep * $favoriteMinute);
    }

    public function partTwo(): string
    {
        $lines = $this->readInputAsLines();
        $parsed = array_map($this->parseLine(...), $lines);
        usort($parsed, static fn(\stdClass $l, \stdClass $r) => $l->datetime <=> $r->datetime);

        [$totalSleep, $sleepMinutes] = $this->computeSleepTimes($parsed);

        $highestFrequency = 0;
        $highestFrequencyGuard = 0;
        $highestFrequencyMinute = 0;
        foreach ($sleepMinutes as $guard => $sleep) {
            [$favoriteMinute, $frequency] = $this->findFavouriteMinuteAndFrequency($sleep);
            if ($frequency > $highestFrequency) {
                $highestFrequency = $frequency;
                $highestFrequencyGuard = $guard;
                $highestFrequencyMinute = $favoriteMinute;
            }
        }

        return (string) ($highestFrequencyGuard * $highestFrequencyMinute);
    }

    private function parseLine(string $line): \stdClass
    {
        return (object) [
            'datetime' => new \DateTimeImmutable(substr($line, 1, 16)),
            'info' => substr($line, 19)
        ];
    }

    private function computeSleepTimes($parsed): array
    {
        $totalSleep = [];
        $sleepMinutes = [];
        $guardOnDuty = 0;
        $sleepStartedAt = 0;
        $wakedUpAt = 0;
        foreach ($parsed as $p) {
            switch ($p->info) {
                case 'falls asleep':
                    $sleepStartedAt = (int) $p->datetime->format('i');
                    break;
                case 'wakes up':
                    $wakedUpAt = (int) $p->datetime->format('i');
                    $sleep = $wakedUpAt - $sleepStartedAt;
                    $sleepMinutes[$guardOnDuty] = [...$sleepMinutes[$guardOnDuty], ...range($sleepStartedAt, $wakedUpAt - 1)];
                    $totalSleep[$guardOnDuty] += $sleep;
                    break;
                default:
                    $parts = explode(' ', $p->info);
                    $guardOnDuty = (int)substr($parts[1], 1);
                    $sleepMinutes[$guardOnDuty] ??= [];
                    $totalSleep[$guardOnDuty] ??= 0;
                    break;
            }
        }

        return [$totalSleep, $sleepMinutes];
    }

    private function findFavouriteMinuteAndFrequency(array $sleepMinutes): array
    {
        $minutesCount = array_count_values($sleepMinutes);
        $favoriteMinute = 0;
        $maxCount = 0;
        foreach (range(0, 59) as $minute) {
            if (($minutesCount[$minute] ?? -1) > $maxCount) {
                $maxCount = $minutesCount[$minute];
                $favoriteMinute = $minute;
            }
        }

        return [$favoriteMinute, $maxCount];
    }
}