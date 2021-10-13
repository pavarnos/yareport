<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRenderInterface;
use LSS\YAReport\Render\RenderInterface;

/**
 * Format as a number
 */
class FloatColumn extends Column
{
    protected ?int $decimalPlaces = null;

    public function __construct(string $id, string $title, string $description = '')
    {
        parent::__construct($id, $title, $description);
        $this->setRightAlign();
    }

    public function setDecimalPlaces(int $decimalPlaces): static
    {
        $this->decimalPlaces = $decimalPlaces;
        return $this;
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
        $value = $row[$this->getID()] ?? null;
        if (is_null($value)) {
            $render->appendBlank();
        } else {
            $render->appendFloat(floatval($value));
        }
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $value = floatval($row[$this->getID()] ?? 0);
        return is_null($this->decimalPlaces) ? (string)$value : number_format($value, $this->decimalPlaces);
    }
}
