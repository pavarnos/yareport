<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   03 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

/**
 * We need two separate interfaces (@see RenderInterface) because for most formats (csv, json, excel etc) the cell
 * value is a direct representation of the source value.
 * For html, it is a bit trickier because we often need to make the value clickable (turn it into a url) or display an
 * icon instead of the value, or perform some other weird unpredictable transformation. This means html must be
 * rendered separately from other views.
 */
interface HtmlRenderInterface
{
    public function escape(string $text): string;
}
