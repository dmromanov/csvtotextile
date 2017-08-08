<?php

namespace App\Command;

use App\Converter\Converter;
use App\FileReader\CsvReader;
use App\OutputStream\OutputStreamFactory;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CsvToTextile Command
 *
 * @package App\Command
 */
class CsvToTextile extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure(): self
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('csvtotextile')
            // the short description shown while running "php bin/console list"
            ->setDescription('Converts a CSV file to a Textile-formatted text table.')
            ->addUsage('[options] --output <file> <input> [<headerRows>], [<headerCols]')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to convert a CSV-file to a Textile-formatted text table.')
            ->addArgument('input', InputArgument::REQUIRED, 'Input CSV file.')
            ->addArgument('headerRows', InputArgument::OPTIONAL, 'Number of header rows.', 0)
            ->addArgument('headerCols', InputArgument::OPTIONAL, 'Amount of header columns.', 0)
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output to a file, ', false)
            ->addOption('csvDelimiter', 'd', InputOption::VALUE_OPTIONAL, 'Values delimiter.', ',')
            ->addOption('csvEnclosure', 'c', InputOption::VALUE_OPTIONAL, 'Values delimiter.', '"')
            ->addOption('csvEscape', 'e', InputOption::VALUE_OPTIONAL, 'Values delimiter.', '\\');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input Console Input
     * @param OutputInterface $output Console Output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $errOutput = $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output;

        $outputStreamName = OutputStreamFactory::STDOUT;
        if ($input->getOption('output')) {
            $outputStreamName = OutputStreamFactory::FILE;
        }
        $outputStream = OutputStreamFactory::build(
            $outputStreamName,
            $output,
            $input->getOption('output')
        );

        $formatter = new Converter();

        $headerRows = $input->getArgument('headerRows');
        $headerCols = $input->getArgument('headerCols');

        try {
            $file = new CsvReader(
                $input->getArgument('input'),
                $input->getOption('csvDelimiter'),
                $input->getOption('csvEnclosure'),
                $input->getOption('csvEscape')
            );

            foreach ($file as $lineNo => $line) {
                $outputStream->writeln($formatter->formatLine($line, $lineNo < $headerRows, $headerCols));
            }

        } catch (Exception $e) {
            $errOutput->writeln($e->getMessage());
            exit(1);
        }

        exit(0);
    }
}
