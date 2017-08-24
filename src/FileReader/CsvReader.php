<?php

namespace CsvToTextile\FileReader;

use SplFileObject;

/**
 * Class CsvReader
 *
 * @package CsvToTextile\FileReader
 */
class CsvReader extends SplFileObject
{
    protected $trim;

    /**
     * CsvReader constructor
     *
     * @param string $path Path to a CSV file to read
     * @param string $delimiter Values delimiter
     * @param string $enclosure Character used for enclosing
     * @param string $escape Character used for escaping
     * @param bool $trim Trim cell values
     */
    public function __construct(
        string $path,
        string $delimiter,
        string $enclosure,
        string $escape,
        bool $trim
    ) {
        parent::__construct($path, 'r');

        $this->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $this->setCsvControl(
            $delimiter,
            $enclosure,
            $escape
        );

        $this->trim = $trim;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $line = parent::current();
        if ($this->trim) {
            $line = array_map(function ($col) {
                return trim($col);
            }, $line);
        }
        return $line;
    }

}
