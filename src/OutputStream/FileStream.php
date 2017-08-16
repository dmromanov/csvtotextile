<?php

namespace CsvToTextile\OutputStream;

use SplFileObject;

/**
 * Class FileStream
 * @package CsvToTextileOutputStream
 */
class FileStream implements OutputStreamInterface
{
    /**
     * @var SplFileObject
     */
    protected $file;

    /**
     * FileStream constructor.
     *
     * @param SplFileObject $file
     */
    public function __construct(SplFileObject $file)
    {
        $this->file = $file;
    }

    /**
     * Writes a message to the output file and adds a newline at the end.
     *
     * @param string $message The message as an array of lines of a single string
     */
    public function writeln(string $message)
    {
        $this->file->fwrite($message . PHP_EOL);
    }
}
