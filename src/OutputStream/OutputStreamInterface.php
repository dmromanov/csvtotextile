<?php

namespace CsvToTextile\OutputStream;

/**
 * Interface OutputStreamInterface
 *
 * @package CsvToTextileOutputStream
 */
interface OutputStreamInterface
{
    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string $message The message as an array of lines of a single string
     */
    public function writeln(string $message);
}
