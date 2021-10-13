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
 * return a json encoded string
 */
class JsonRender implements RenderInterface
{
    private mixed $currentValue = null;

    public function render(Report $report, iterable $data): array
    {
        $output  = ['meta' => [], 'data' => []];
        $columns = $report->getVisibleColumns();

        foreach ($columns as $column) {
            $type = str_replace('Column', '', array_reverse(explode('\\', $column::class))[0]) ?: 'String';
            $column->renderTitle($this);
            $output['meta'][$column->getID()] = [
                'title'       => $this->currentValue,
                'type'        => $type,
                'description' => $column->getDescription(),
            ];
        }

        foreach ($data as $row) {
            $renderedRow = [];
            foreach ($columns as $column) {
                $column->renderCell($this, $row);
                $renderedRow[$column->getID()] = $this->currentValue;
            }
            $output['data'][] = $renderedRow;
        }
        return $output;
    }

    public function appendString(string $value): void
    {
        $this->currentValue = $value;
    }

    public function appendInteger(int $value): void
    {
        $this->currentValue = $value;
    }

    public function appendFloat(float $value): void
    {
        $this->currentValue = $value;
    }

    public function appendBoolean(bool $value): void
    {
        $this->currentValue = $value;
    }

    public function appendBlank(): void
    {
        $this->currentValue = null;
    }
}
