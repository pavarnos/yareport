<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   03 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender\Row;

use LSS\YAReport\Render\HtmlRender\TableElement;

/**
 * shouldApply($row) should return boolean if this row should have $style applied to it
 */
class ConditionalRowStyle extends AbstractRowDecorator
{
    /**
     * @param string               $style
     * @param callable(array):bool $shouldApply
     */
    public function __construct(private string $style, private $shouldApply)
    {
    }

    public function innerRow(TableElement $row, array $columns, array $data): void
    {
        if (($this->shouldApply)($data)) {
            $row->addStyle($this->style);
        }
    }
}
