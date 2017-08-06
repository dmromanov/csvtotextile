<?php

namespace App\OutputStream;

/**
 * Class FileStream
 * @package App\OutputStream
 */
class FileStream implements OutputStreamInterface
{
    /**
     * @var \SplFileObject
     */
    protected $file;

    /**
     * FileStream constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->file = new \SplFileObject($filename, 'w');
    }

    /**
     * Writes a message to the output file and adds a newline at the end.
     *
     * @param string $message The message as an array of lines of a single string
     */
    public function writeln(string $message): void
    {
        $this->file->fwrite($message . PHP_EOL);
    }
}
