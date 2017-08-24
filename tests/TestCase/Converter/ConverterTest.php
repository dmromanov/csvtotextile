<?php

namespace CsvToTextile\Test\TestCase\Converter;

use CsvToTextile\Converter\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Class ConverterTest
 * @package CsvToTextileTest\TestCase\Converter
 *
 * @property Converter $Converter
 */
class ConverterTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->Converter = new Converter();
    }

    public function testFormatLineBlank()
    {
        $expected = '';
        $result = $this->Converter->formatLine([], false, false);
        $this->assertSame($expected, $result);
    }

    public function testFormatLine()
    {
        $expected = '| foo | bar |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar'],
            false,
            false
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatLineHeaderRow()
    {
        $expected = '|_. foo |_. bar |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar'],
            false,
            true
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatLineHeaderCols()
    {
        $expected = '|_. foo | bar | test |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar', 'test'],
            false,
            false
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatLineTrimValue()
    {
        $expected = '|_. foo |_. bar |_. baz |';
        $result = $this->Converter->formatLine(
            ['foo    ', '    bar', '   baz    '],
            true,
            true
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatAligned()
    {
        $expected = '|_. foo |_. bar |_. baz |';
        $result = $this->Converter->formatLine(
            ['foo    ', '    bar', '   baz    '],
            true,
            true
        );
        $this->assertSame($expected, $result);
    }

    public function testCalculateWidths()
    {
        $rows = [
            ['foo', 'bar'],
            ['foo    ', 'bar '],
        ];

        $expected = [7, 4];
        $result = $this->Converter->calculateWidths($rows);
        $this->assertSame($expected, $result);
    }
}
