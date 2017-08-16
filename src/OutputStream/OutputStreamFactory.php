<?php

namespace CsvToTextile\OutputStream;

use SplFileObject;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class OutputStreamFactory
 * @package CsvToTextileOutputStream
 */
class OutputStreamFactory {
    const STDOUT = 'stdout';
    const FILE = 'file';

    /**
     * Build Output Stream Object
     *
     * @param string $outputStreamType Type of an output stream.
     * @param OutputInterface $stdout Console Output object.
     * @param string $filename Filename to write into.
     *
     * @return OutputStreamInterface
     */
    public static function build(string $outputStreamType, OutputInterface $stdout, string $filename): OutputStreamInterface {
        switch ($outputStreamType) {
            case static::STDOUT:
                return new ConsoleStream($stdout);
                break;

            case static::FILE:
                return new FileStream(new SplFileObject($filename, 'w'));
                break;

            default:
                throw new \InvalidArgumentException('Invalid output stream name.');
                break;
        }
    }
}
