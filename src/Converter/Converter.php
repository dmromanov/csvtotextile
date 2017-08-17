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
    public function formatLine(array $row, bool $trim, bool $isHeaderRow, $headerColsNum): string
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
                $this->formatCols($headerCols, $trim, '|_.'),
                $this->formatCols($dataCols, $trim, '|')
            ],
            function ($value) {
                return (bool)$value;
            }
        );

        return implode(' ', $formattedChunks) . ' |';
    }

    protected function formatCols(array $cols, bool $trim, string $delimiter): string
    {
        if (empty($cols)) {
            return '';
        }

        if ($trim) {
            $cols = array_map(function($col) {
                return trim($col);
            }, $cols);
        }

        return $delimiter . ' ' . implode(' ' . $delimiter . ' ', $cols);
    }
}
