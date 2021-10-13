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
 * Calculate the value of the cell from other information in the row
 */
class CalculatedColumn extends Column
{
    /** @var ?callable(array):string */
    private $render = null;

    /** @var ?callable(array):string */
    private $renderHtml = null;

    /**
     * set the function to calculate / render the value for the cell (must be single valued).
     * The callable must take a single array parameter (the $row from the database) and
     * return a plain text string
     * @param callable(array):string $render
     * @return static
     */
    public function setRender(callable $render): static
    {
        $this->render = $render;
        return $this;
    }

    /**
     * set the function to calculate / render the value for the cell (must be single valued).
     * The callable must take a single array parameter (the $row from the database) and
     * return an escaped html string
     * @param callable(array):string $render
     * @return static
     */
    public function setRenderHtml(callable $render): static
    {
        $this->renderHtml = $render;
        return $this;
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
        if (is_null($this->render)) {
            parent::renderCell($render, $row);
        } else {
            $render->appendString((string)($this->render)($row));
        }
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        if (is_null($this->renderHtml)) {
            return parent::renderCellHtml($render, $row);
        }
        return (string)($this->renderHtml)($row);
    }
}
