<?php

declare(strict_types=1);

namespace Dannyvdsluijs\AdventOfCode2018;

use Dannyvdsluijs\AdventOfCode2018\Concerns\ContentReader;

class Day07 implements PuzzleDay
{
    use ContentReader;

    private array $nodes = [];

    public function partOne(): string
    {
        $dependencies = [];
        $steps = [];
        $finishedSteps = [];
        foreach ($this->readInputAsLines() as $line) {
            [, $dependency, , , , , , $step] = explode(' ', $line);

            $steps[] = $dependency;
            $steps[] = $step;
            $dependencies[] = ['dependency' => $dependency, 'step' => $step];
        }

        $steps = array_unique($steps);
        $remainingSteps = array_combine($steps, $steps);

        while (count($remainingSteps) > 0) {
            $stepsWithoutDependencies = [];
            foreach ($remainingSteps as $step) {
                $stepDependencies = array_filter($dependencies, static fn(array $d) => $d['step'] === $step);

                $stepDependencies =  array_diff(array_map(static fn(array $d) => $d['dependency'], $stepDependencies), $finishedSteps);

                if (count($stepDependencies) === 0) {
                    $stepsWithoutDependencies[] = $step;
                }
            }

            if ($stepsWithoutDependencies === []) {
                throw new \RuntimeException('Unable to find next step without dependency');
            }

            sort($stepsWithoutDependencies);

            // shift and mark as resolved
            $currentStep = array_shift($stepsWithoutDependencies);
            $finishedSteps[] = $currentStep;
            unset($remainingSteps[$currentStep]);
        }

        return implode($finishedSteps);
    }

    public function partTwo(): string
    {
        $dependencies = [];
        $steps = [];
        $finishedSteps = [];
        $workers = [1 => [], 2 => [], 3 => [], 4 => [], 5 => []];
        $baseTime = 60;
        $t = 0;

        foreach ($this->readInputAsLines() as $line) {
            [, $dependency, , , , , , $step] = explode(' ', $line);

            $steps[] = $dependency;
            $steps[] = $step;
            $dependencies[] = ['dependency' => $dependency, 'step' => $step];
        }

        $steps = array_unique($steps);
        $remainingSteps = array_combine($steps, $steps);

        while (count($remainingSteps) > 0) {
            // See if workers are done
            foreach ($workers as $key => $worker) {
                if ($worker === []) {
                    continue;
                }

                if ($worker['readyAt'] === $t) {
                    $finishedSteps[] = $worker['step'];
                    $workers[$key] = [];
                }
            }

            // Find available workers
            $availableWorkers = array_filter($workers, fn(array $worker) => $worker === []);

            // If no workers quit early
            if ($availableWorkers === []) {
                $t++;
                continue;
            }

            // Find steps without dependecies
            $stepsWithoutDependencies = [];
            foreach ($remainingSteps as $step) {
                $stepDependencies = array_filter($dependencies, static fn(array $d) => $d['step'] === $step);

                $stepDependencies =  array_diff(array_map(static fn(array $d) => $d['dependency'], $stepDependencies), $finishedSteps);

                if (count($stepDependencies) === 0) {
                    $stepsWithoutDependencies[] = $step;
                }
            }

            // Return early if no available step at this moment in time
            if ($stepsWithoutDependencies === []) {
                $t++;
                continue;
            }

            // Still alphabetical
            sort($stepsWithoutDependencies);

            // Start workers
            foreach ($availableWorkers as $key => $worker) {
                $currentStep = array_shift($stepsWithoutDependencies);
                $workers[$key] = ['step' => $currentStep, 'readyAt' => $t + $baseTime + ord($currentStep) - 64];
                unset($remainingSteps[$currentStep]);

                if ($stepsWithoutDependencies === []) {
                    break;
                }
            }

            $t++;
        }

        $readyAts = array_map(fn(array $w) => $w['readyAt'] ?? 0, $workers);

        return (string) max($readyAts);
    }
}