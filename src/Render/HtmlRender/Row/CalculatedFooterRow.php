<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   03 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender\Row;

use LSS\YAReport\Column\Column;
use LSS\YAReport\Render\HtmlRender\TableElement;

class CalculatedFooterRow extends AbstractRowDecorator
{
    /**
     * @param callable(Column):string $getCellHtml
     */
    public function __construct(private $getCellHtml)
    {
    }

    /** @inheritdoc */
    public function afterLastRow(array $columns): string
    {
        $cells = '';
        foreach ($columns as $column) {
            $cells .= (new TableElement('td'))
                ->addStyles($column->getCSSStyles())
                ->setContent(($this->getCellHtml)($column))
                ->__toString();
        }
        return (new TableElement('tr'))->setContent($cells)->__toString();
    }
}
