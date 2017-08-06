<?php

namespace App\Test\TestCase\Converter;

use App\Converter\Converter;
use PHPUnit\Framework\TestCase;

/**
 * Class ConverterTest
 * @package App\Test\TestCase\Converter
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
        $result = $this->Converter->formatLine([], false, 0);
        $this->assertSame($expected, $result);
    }

    public function testFormatLine()
    {
        $expected = '| foo | bar |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar'],
            false,
            0
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatLineHeaderRow()
    {
        $expected = '|_. foo |_. bar |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar'],
            true,
            0
        );
        $this->assertSame($expected, $result);
    }

    public function testFormatLineHeaderCols()
    {
        $expected = '|_. foo | bar | test |';
        $result = $this->Converter->formatLine(
            ['foo', 'bar', 'test'],
            false,
            1
        );
        $this->assertSame($expected, $result);
    }
}
