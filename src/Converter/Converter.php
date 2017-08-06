<?php

namespace App\Converter;

/**
 * Converter
 *
 * Class Converter
 * @package App\Converter
 */
class Converter
{
    /**
     * @param array $row
     * @param bool $isHeaderRow
     * @param int $headerCols
     *
     * @return string
     */
    public function formatLine(array $row, bool $isHeaderRow, $headerColsNum): string
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
                $this->formatCols($headerCols, '|_.'),
                $this->formatCols($dataCols, '|')
            ],
            function ($value) {
                return (bool)$value;
            }
        );

        return implode(' ', $formattedChunks) . ' |';
    }

    protected function formatCols(array $cols, string $delimiter): string
    {
        if (empty($cols)) {
            return '';
        }

        return $delimiter . ' ' . implode(' ' . $delimiter . ' ', $cols);
    }
}
