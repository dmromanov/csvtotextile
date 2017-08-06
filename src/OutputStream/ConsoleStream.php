<?php

namespace App\OutputStream;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleStream
 *
 * @package App\OutputStream
 */
class ConsoleStream implements OutputStreamInterface
{
    /**
     * @var OutputInterface
     */
    protected $stdout;

    /**
     * ConsoleStream constructor.
     *
     * @param OutputInterface $stdout
     */
    public function __construct(OutputInterface $stdout)
    {
        $this->stdout = $stdout;
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string $message The message as an array of lines of a single string
     */
    public function writeln(string $message): void
    {
        $this->stdout->writeln($message);
    }
}
