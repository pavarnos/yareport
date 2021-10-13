<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRenderInterface;

/**
 * Format as a number
 */
class PercentColumn extends FloatColumn
{
    public const PERCENT_SYMBOL = '%';

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $value = $row[$this->getID()] ?? '';
        if (empty($value)) {
            return '';
        }
        return parent::renderCellHtml($render, $row) . self::PERCENT_SYMBOL;
    }
}
