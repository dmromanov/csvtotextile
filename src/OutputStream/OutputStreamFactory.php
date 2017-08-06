<?php

namespace App\OutputStream;

use Symfony\Component\Console\Output\OutputInterface;

class OutputStreamFactory {
    const STDOUT = 'stdout';
    const FILE = 'file';

    public static function build(string $outputStreamName, OutputInterface $stdout, string $filename): OutputStreamInterface {
        switch ($outputStreamName) {
            case static::STDOUT:
                return new ConsoleStream($stdout);
                break;

            case static::FILE:
                return new FileStream($filename);
                break;

            default:
                throw new \InvalidArgumentException('Invalid output stream name.');
                break;
        }
    }
}
