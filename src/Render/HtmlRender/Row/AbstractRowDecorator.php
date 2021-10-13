<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender\Row;

use LSS\YAReport\Column\Column;
use LSS\YAReport\Render\HtmlRender\TableElement;

abstract class AbstractRowDecorator implements RowDecoratorInterface
{
    /**
     * @param Column[] $columns
     */
    public function beforeFirstRow(array $columns): string
    {
        return '';
    }

    /**
     * @param TableElement $row
     * @param Column[]     $columns
     * @param array        $data for the row
     */
    public function innerRow(TableElement $row, array $columns, array $data): void
    {
    }

    /**
     * @param Column[] $columns
     */
    public function afterLastRow(array $columns): string
    {
        return '';
    }
}
