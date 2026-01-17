<?php

namespace CsvToTextile\Test\TestCase\OutputStream;

use CsvToTextile\OutputStream\FileStream;
use PHPUnit\Framework\TestCase;
use SplFileObject;

class FileStreamTest extends TestCase
{
    /**
     * @var FileStream
     */
    protected $Stream;

    /**
     * @var SplFileObject|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileMock;

    /**
     * Set Up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->fileMock = $this->getMockBuilder(SplFileObject::class)
            ->enableOriginalConstructor()
            ->disableOriginalClone()
            ->setConstructorArgs(['php://memory'])
            ->getMock();

        $this->Stream = new FileStream($this->fileMock);
    }

    /**
     * Test Writeln
     */
    public function testWriteln()
    {
        $this->fileMock
            ->expects($this->once())
            ->method('fwrite')
            ->with('foo bar' . PHP_EOL);

        $this->Stream->writeln('foo bar');
    }
}
