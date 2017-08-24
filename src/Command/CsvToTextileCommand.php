<?php

namespace CsvToTextile\Command;

use CsvToTextile\Converter\Converter;
use CsvToTextile\FileReader\CsvReader;
use CsvToTextile\OutputStream\OutputStreamFactory;
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
 * @package CsvToTextile\Command
 */
class CsvToTextileCommand extends Command
{

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('csvtotextile')

            ->setDescription('Converts a CSV file to a Textile-formatted text table.')

            ->addUsage('[options] --output <file> <input> [<headerRows>], [<headerCols]')

            ->setHelp('This command allows you to convert a CSV-file to a Textile-formatted text table.')

            ->addArgument('input', InputArgument::REQUIRED, 'Input CSV file.')
            ->addArgument('headerRows', InputArgument::OPTIONAL, 'Number of header rows.', 0)
            ->addArgument('headerCols', InputArgument::OPTIONAL, 'Amount of header columns.', 0)

            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output to a file, ', false)
            ->addOption('csvDelimiter', 'd', InputOption::VALUE_OPTIONAL, 'Values delimiter.', ',')
            ->addOption('csvEnclosure', 'c', InputOption::VALUE_OPTIONAL, 'Values delimiter.', '"')
            ->addOption('csvEscape', 'e', InputOption::VALUE_OPTIONAL, 'Values delimiter.', '\\')
            ->addOption('trim', 't', InputOption::VALUE_NONE, 'Trim spaces.')
            ->addOption('align', 'a', InputOption::VALUE_NONE, 'Align columns.');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input Console Input
     * @param OutputInterface $output Console Output
     *
     * @return int Exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
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

        if (!file_exists($input->getArgument('input'))) {
            $errOutput->writeln('<error>No such file.</error>');
            return 4;
        }

        $formatter = new Converter();

        $headerRows = $input->getArgument('headerRows');
        $headerCols = $input->getArgument('headerCols');

        try {
            $file = new CsvReader(
                $input->getArgument('input'),
                $input->getOption('csvDelimiter'),
                $input->getOption('csvEnclosure'),
                $input->getOption('csvEscape'),
                $input->getOption('trim')
            );

            $widths = [];
            if ($input->getOption('align')) {
                $rows = [];
                foreach ($file as $row) {
                    $rows[] = $row;
                }
                $widths = $formatter->calculateWidths($rows);
                $file->seek(0);
            }

            foreach ($file as $lineNo => $line) {
                $outputStream->writeln($formatter->formatLine(
                    $line,
                    $lineNo < $headerRows,
                    $headerCols,
                    $widths
                ));
            }

        } catch (Exception $e) {
            $errOutput->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        return 0;
    }
}
