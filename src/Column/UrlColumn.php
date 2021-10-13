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
 * a link to a web site
 */
class UrlColumn extends Column
{
    public const EXTERNAL_LINK_ATTRIBUTES = ' target="_blank" rel="noopener nofollow"';

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $url = $row[$this->getID()] ?? '';
        if (empty($url)) {
            return '';
        }
        if (mb_strpos($url, '://') === false && $url[0] != '/') {
            $url = 'https://' . $url;
        }
        $url    = filter_var($url, FILTER_SANITIZE_URL) ?: '';
        $target = ($url[0] == '/') ? '' : self::EXTERNAL_LINK_ATTRIBUTES; // force a new browser tab if off site
        return '<a href="' . $url . '"' . $target . '>' . $url . '</a>';
    }
}
