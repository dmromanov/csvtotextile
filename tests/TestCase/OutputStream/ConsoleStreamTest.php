<?php

namespace CsvToTextile\Test\TestCase\OutputStream;

use CsvToTextile\OutputStream\ConsoleStream;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

class ConsoleStreamTest extends TestCase
{
    /**
     * @var ConsoleStream
     */
    protected $Stream;

    /**
     * @var BufferedOutput
     */
    protected $output;

    /**
     * Set Up
     */
    protected function setUp()
    {
        parent::setUp();
        $this->output = new BufferedOutput();
        $this->Stream = new ConsoleStream($this->output);
    }

    /**
     * Test Writeln
     */
    public function testWriteln()
    {
        $this->Stream->writeln('foo bar');
        $this->assertSame('foo bar' . PHP_EOL, $this->output->fetch());
    }

}
