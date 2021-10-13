<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\RenderInterface;

/**
 * something visible in html but hidden everywhere else eg a menu column, or a bulk actions checkbox.
 * You'd also normally add a hidden-print style because these are not useful when printed
 */
class HtmlOnlyColumn extends Column
{
    public function renderTitle(RenderInterface $render): void
    {
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
    }
}
