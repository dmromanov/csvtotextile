<?php

namespace App\Test\TestCase\FileReader;

use App\FileReader\CsvReader;
use PHPUnit\Framework\TestCase;
use SplFileObject;

/**
 * Class CsvReaderTest
 */
class CsvReaderTest extends TestCase
{

    /**
     * Test Constructor
     */
    public function testConstructor()
    {
        /** @var CsvReader|\PHPUnit_Framework_MockObject_MockObject $csv */
        $csv = new CsvReader(
            'php://memory',
            '1',
            '2',
            '3'
        );

        $expected = SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE;
        $result = $csv->getFlags();
        $this->assertSame($expected, $result);

        $expected = ['1', '2', '3'];
        $result = $csv->getCsvControl();
        $this->assertSame($expected, $result);
    }

}
