#!php
<?php

use Dannyvdsluijs\AdventOfCode2015;

require_once 'vendor/autoload.php';

ini_set('memory_limit','2048M');

if ($argc < 2 || $argc > 3 || !is_numeric($argv[1])) {
    print("Usage ./run.php <day> [part]\r\n");
    exit(255);
}

$className = sprintf("\Dannyvdsluijs\AdventOfCode2015\Day%02d", $argv[1]);
$object = new $className();
$part = (int) ($argv[2] ?? 1);

$answer = match($part) {
    1 => $object->partOne(),
    2 => $object->partTwo(),
};


printf("The correct answer for day %d part %d is: %s\r\n", $argv[1], $part,  $answer);