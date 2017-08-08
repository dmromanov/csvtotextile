<?php

namespace App\Test\TestCase\OutputStream;

use App\OutputStream\OutputStreamFactory;
use App\OutputStream\OutputStreamInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * Class OutputStreamFactoryTest
 * @package App\Test\TestCase\OutputStream
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
