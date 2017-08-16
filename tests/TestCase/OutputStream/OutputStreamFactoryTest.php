<?php

namespace CsvToTextile\Test\TestCase\OutputStream;

use CsvToTextile\OutputStream\OutputStreamFactory;
use CsvToTextile\OutputStream\OutputStreamInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class OutputStreamFactoryTest
 * @package CsvToTextileTest\TestCase\OutputStream
 */
class OutputStreamFactoryTest extends TestCase
{

    /**
     * Test Build
     */
    public function testBuild()
    {
        $this->assertInstanceOf(OutputStreamInterface::class, OutputStreamFactory::build(
            OutputStreamFactory::FILE,
            new BufferedOutput(),
            'php://memory'
        ));

        $this->assertInstanceOf(OutputStreamInterface::class, OutputStreamFactory::build(
            OutputStreamFactory::STDOUT,
            new BufferedOutput(),
            'php://memory'
        ));
    }

    /**
     * Test Build Invalid
     */
    public function testBuildInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);

        OutputStreamFactory::build(
            'foo bar',
            new BufferedOutput(),
            'php://memory'
        );
    }

}
