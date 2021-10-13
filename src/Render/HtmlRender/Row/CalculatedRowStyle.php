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
 * getStyle($row) should return the css class you want to apply to the row, or '' if nothing
 */
class CalculatedRowStyle extends AbstractRowDecorator
{
    /**
     * @param callable(array):string $getStyle
     */
    public function __construct(private $getStyle)
    {
    }

    public function innerRow(TableElement $row, array $columns, array $data): void
    {
        $row->addStyle(($this->getStyle)($data));
    }
}
