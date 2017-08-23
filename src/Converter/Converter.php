<?php

namespace CsvToTextile\Converter;

/**
 * Converter
 *
 * Class Converter
 * @package CsvToTextileConverter
 */
class Converter
{
    /**
     * Formats a line to a Textile-row
     *
     * @param array $row
     * @param bool $trim Trim spaces in cells
     * @param bool $isHeaderRow
     * @param int $headerCols
     *
     * @return string
     */
    public function formatLine(array $row, bool $trim, bool $isHeaderRow, $headerColsNum, array $colsWidths = []): string
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
                return $chunk[0];
            }, $chunks);
        }

        $formattedChunks = array_filter(
            [
                $this->formatCols($headerCols, $colsWidths, $trim, '|_.'),
                $this->formatCols($dataCols, $colsWidths, $trim, '|')
            ],
            function ($value) {
                return (bool)$value;
            }
        );

        return implode(' ', $formattedChunks) . ' |';
    }

    protected function formatCols(array $cols, array $widths, bool $trim, string $delimiter): string
    {
        if (empty($cols)) {
            return '';
        }

        if ($trim) {
            $cols = array_map(function($col) {
                return trim($col);
            }, $cols);
        }

        if (!empty($widths)) {
            $cols = array_map(function ($row, $i) use ($widths) {
                return $row . str_repeat(' ', $widths[$i] - mb_strlen($row));
            }, $cols, array_keys($cols));
        }

        return $delimiter . ' ' . implode(' ' . $delimiter . ' ', $cols);
    }

    /**
     * Calculate the column widths
     *
     * @param array $rows The rows on which the columns width will be calculated on.
     * @param bool $trim
     *
     * @return array
     */
    public function calculateWidths(array $rows, bool $trim)
    {
        $widths = [];
        foreach ($rows as $key => $line) {
            foreach (array_values($line) as $k => $v) {
                if ($trim) {
                    $v = trim($v);
                }
                $columnLength = mb_strwidth($v);
                if ($columnLength >= (isset($widths[$k]) ? $widths[$k] : 0)) {
                    $widths[$k] = $columnLength;
                }
            }
        }
        return $widths;
    }
}
