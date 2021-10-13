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
 * Format as a money value
 */
class MoneyColumn extends FloatColumn
{
    protected string $currencySymbol = '';

    public function setCurrencySymbol(string $currencySymbol): static
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $value = floatval($row[$this->getID()] ?? 0);
        return $this->currencySymbol . number_format($value, $this->decimalPlaces ?? 2);
    }
}
