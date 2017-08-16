<?php

namespace CsvToTextile\FileReader;

use SplFileObject;

class CsvReader extends SplFileObject
{
    /**
     * CsvReader constructor.
     *
     * @param string $path Path to a CSV file to read.
     * @param string $delimiter Values delimiter.
     * @param string $enclosure Character used for enclosing.
     * @param string $escape Character used for escaping.
     */
    public function __construct(
        string $path,
        string $delimiter,
        string $enclosure,
        string $escape
    ) {
        parent::__construct($path, 'r');
        $this->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $this->setCsvControl(
            $delimiter,
            $enclosure,
            $escape
        );
    }

}
