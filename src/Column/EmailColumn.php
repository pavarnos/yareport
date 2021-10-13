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
 * Format an email address as a link where it makes sense to do so
 */
class EmailColumn extends Column
{
    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $email = $row[$this->getID()] ?? '';
        if (empty($email)) {
            return '';
        }
        $email = $render->escape($email);
        return '<a href="mailto:' . $email . '">' . $email . '</a>';
    }
}
