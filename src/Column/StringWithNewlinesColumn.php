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
 * format the string so it displays as formatted by the user eg with line feeds included
 */
class StringWithNewlinesColumn extends Column
{
    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        return nl2br(trim($render->escape($row[$this->getID()] ?? '')));
    }
}
