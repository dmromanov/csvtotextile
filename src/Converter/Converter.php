<?php

namespace CsvToTextile\Converter;

/**
 * Class Converter
 *
 * @package CsvToTextileConverter
 */
class Converter
{
    /**
     * Formats a line to a Textile-row
     *
     * @param array $row A row from a table
     * @param bool $isHeaderRow Defines if the row is a header row
     * @param int $headerColsNum Defines if the row is a header column
     * @param int[] $colsWidths Array of columns widths
     *
     * @return string
     */
    public function formatLine(array $row, bool $isHeaderRow, $headerColsNum, array $colsWidths = []): string
    {
        if (empty($row)) {
            return '';
        }

        if ($isHeaderRow) {
            $headerColsNum = count($row);
        }

        $headerCols = [];
        $dataCols = $row;
        if ($headerColsNum) {
            $chunks = array_chunk($row, $headerColsNum);
            $headerCols = array_shift($chunks);
            $dataCols = array_map(function ($chunk) {
                return current($chunk);
            }, $chunks);
        }

        $formattedChunks = array_filter(
            [
                $this->formatCols($headerCols, $colsWidths, 0, '|_.'),
                $this->formatCols($dataCols, $colsWidths, count($headerCols), '|')
            ],
            function ($value) {
                return (bool)$value;
            }
        );

        return implode(' ', $formattedChunks) . ' |';
    }

    /**
     * Format columns of a single row
     *
     * @param array $cols Array of column values
     * @param array $widths Array of column widths
     * @param int $offset An offset for column widths keys, we might be formatting not from a start of a row
     * @param string $separator String used for separating different columns in the resulting table
     *
     * @return string
     */
    protected function formatCols(array $cols, array $widths, int $offset, string $separator): string
    {
        if (empty($cols)) {
            return '';
        }

        $indexes = array_map(function ($i) use ($offset) {
            return $i + $offset;
        }, array_keys($cols));

        if (!empty($widths)) {
            $cols = array_map(
                function ($row, $i) use ($widths) {
                    return $row . str_repeat(' ', $widths[$i] - mb_strlen($row));
                },
                array_values($cols),
                $indexes
            );
        }

        return $separator . ' ' . implode(' ' . $separator . ' ', $cols);
    }

    /**
     * Calculate the column widths
     *
     * @param array $lines The rows on which the columns width will be calculated on.
     *
     * @return array
     */
    public function calculateWidths(array $lines): array
    {
        $widths = [];
        foreach ($lines as $key => $row) {
            foreach (array_values($row) as $k => $value) {
                if (!isset($widths[$k])) {
                    $widths[$k] = null;
                }
                $columnLength = mb_strlen($value);
                $widths[$k] = max($widths[$k], $columnLength);
            }
        }
        return $widths;
    }
}
