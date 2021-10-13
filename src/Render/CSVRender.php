<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   02 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Report;

/**
 * generate a string containing comma separated values
 */
class CSVRender implements RenderInterface
{
    protected const QUOTE   = '"';
    protected const NEWLINE = PHP_EOL;

    private array $currentRow = [];

    public function render(Report $report, iterable $data): string
    {
        $output = '';
        // render header
        foreach ($report->getVisibleColumns() as $column) {
            $column->renderTitle($this);
        }
        $output           .= join(',', $this->currentRow) . static::NEWLINE;
        $this->currentRow = [];

        // render data
        $columns = $report->getVisibleColumns();
        foreach ($data as $rawRow) {
            foreach ($columns as $column) {
                $column->renderCell($this, $rawRow);
            }
            $output           .= join(',', $this->currentRow) . static::NEWLINE;
            $this->currentRow = [];
        }

        return $output;
    }

    public function appendString(string $value): void
    {
        $this->currentRow[] = static::QUOTE .
            str_replace(static::QUOTE, static::QUOTE . static::QUOTE, $value) .
            static::QUOTE;
    }

    public function appendInteger(int $value): void
    {
        $this->currentRow[] = $value;
    }

    public function appendFloat(float $value): void
    {
        $this->currentRow[] = $value;
    }

    public function appendBoolean(bool $value): void
    {
        $this->currentRow[] = $value ? 1 : 0;
    }

    public function appendBlank(): void
    {
        $this->currentRow[] = '';
    }
}
