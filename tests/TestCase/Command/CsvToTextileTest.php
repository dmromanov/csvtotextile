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
 * @package CsvToTextile\Test\TestCase\Command
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

        $payload = [
            [1, 'foo', 'bar'],
            [2, 'foo', 'barbaz'],
        ];

        $res = fopen($this->tmpFile, 'w');
        foreach ($payload as $row) {
            fputcsv($res, $row);
        }
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
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),
        ]);

        $this->assertSame(1, $exitCode);
    }

    public function testSimpleTable()
    {
        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => $this->tmpFile,
        ]);

        $result = $commandTester->getDisplay();
$expected = <<<TEXTILE
| 1 | foo | bar |
| 2 | foo | barbaz |

TEXTILE;

        $this->assertSame($expected, $result);
        $this->assertSame(0, $exitCode);
    }

    public function testNotExistent()
    {

        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => '-not-existent-file-',
        ]);

        $this->assertSame(4, $exitCode);
    }

    public function testTableWithHeaders()
    {
        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => $this->tmpFile,
            'headerRows' => 1,
            'headerCols' => 1,
        ]);

        $result = $commandTester->getDisplay();
        $expected = <<<TEXTILE
|_. 1 |_. foo |_. bar |
|_. 2 | foo | barbaz |

TEXTILE;

        $this->assertSame($expected, $result);
        $this->assertSame(0, $exitCode);
    }

    public function testTableAligned()
    {
        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => $this->tmpFile,
            'align',
        ]);

        $result = $commandTester->getDisplay();
        $expected = <<<TEXTILE
| 1 | foo | bar    |
| 2 | foo | barbaz |

TEXTILE;

        $this->assertSame($expected, $result);
        $this->assertSame(0, $exitCode);
    }

    public function testTableAlignedWithHeaders()
    {
        $application = new Application(self::$kernel);
        $application->add(new CsvToTextileCommand());

        $command = $application->find('csvtotextile');
        $commandTester = new CommandTester($command);
        $exitCode = $commandTester->execute([
            'command' => $command->getName(),

            // pass arguments to the helper
            'input' => $this->tmpFile,
            'headerRows' => 1,
            'headerCols' => 1,
            'align',
        ]);

        $result = $commandTester->getDisplay();
        $expected = <<<TEXTILE
|_. 1 |_. foo |_. bar  |
|_. 2 | foo   | barbaz |

TEXTILE;

        $this->assertSame($expected, $result);
        $this->assertSame(0, $exitCode);
    }
}
