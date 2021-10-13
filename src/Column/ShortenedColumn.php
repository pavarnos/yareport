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
 * shorten a string so it fits on one line
 */
class ShortenedColumn extends Column
{
    /** @var int longest string that will be displayed */
    protected int $maxLength = 100;

    public function setMaxLength(int $length): static
    {
        assert($length > 3); // 3 for the '...' at the end of the string
        $this->maxLength = $length;
        return $this;
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $text   = $row[$this->getID()] ?? '';
        $length = strlen($text);
        if ($length <= $this->maxLength) {
            return $render->escape($text);
        }
        return $render->escape(\Safe\substr($text, 0, $this->maxLength - 3) . '...');
    }
}
