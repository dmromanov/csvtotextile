<?php

namespace CsvToTextile\Test\TestCase\Command;

use CsvToTextile\Command\CsvToTextileCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class CsvToTextileTest
 *
 * @package CsvToTextileTest\TestCase\Command
 */
class CsvToTextileTest extends KernelTestCase
{
    protected $tmpFile;

    public function setUp()
    {
        register_shutdown_function(function () {
            if (file_exists($this->tmpFile)) {
                unlink($this->tmpFile);
            }
        });
        $this->tmpFile = tempnam(null, 'csvtotextile');

        $res = fopen($this->tmpFile, 'w');
        fputcsv($res, [
            1, 'foo', 'bar',
        ]);
        fputcsv($res, [
            2, 'foo', 'bar',
        ]);
        fclose($res);
    }

    public function testExecWoArguments()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "input")');

        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        ));
    }

    public function testSimpleTable()
    {
        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => $this->tmpFile,
        ));

        $result = $commandTester->getDisplay();
$expected = <<<TEXTILE
| 1 | foo | bar |
| 2 | foo | bar |

TEXTILE;

        $this->assertSame($expected, $result);
    }
}
