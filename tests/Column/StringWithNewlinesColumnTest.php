<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class StringWithNewlinesColumnTest extends ColumnTest
{
    public function getSubject(
        string $id = 'a',
        string $title = 'b',
        string $description = ''
    ): StringWithNewlinesColumn {
        return new StringWithNewlinesColumn($id, $title, $description);
    }

    public function testRenderCellHtmlWithNewlines(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => PHP_EOL . 'baz' . PHP_EOL . '"bat' . PHP_EOL . PHP_EOL];
        $render  = new HtmlRender();
        self::assertEquals("baz<br />\n&quot;bat", $subject->renderCellHtml($render, $row));
    }
}
