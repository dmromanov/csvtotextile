<?php
require __DIR__.'/vendor/autoload.php';

use App\Command\CsvToTextileCommand;
use Symfony\Component\Console\Application;

$command = new CsvToTextileCommand();
$application = new Application('CsvToTextile', '1.0.0');

$application->add($command);
$application->setDefaultCommand($command->getName(), true);

$application->run();
