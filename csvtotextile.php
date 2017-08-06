#!/usr/bin/env php
<?php
require __DIR__.'/vendor/autoload.php';

use App\Command\CsvToTextile;
use Symfony\Component\Console\Application;

$command = new CsvToTextile();
$application = new Application('CsvToTextile', '1.0.0');

$application->add($command);
$application->setDefaultCommand($command->getName(), true);

$application->run();
