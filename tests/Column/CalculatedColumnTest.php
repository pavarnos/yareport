<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;
use LSS\YAReport\Render\RenderInterface;

class CalculatedColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): CalculatedColumn
    {
        return new CalculatedColumn($id, $title, $description);
    }

    // testRenderCell tests it with no callback
    // testRenderCellHtml tests it with no callback

    public function testRenderCellWithCallable(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 'baz'];
        $subject->setRender(fn(array $row): string => $row[$id] . 'xx');
        $render = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendString')->with($row[$id] . 'xx');
        $subject->renderCell($render, $row);
    }

    public function testRenderCellHtmlWithCallable(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 'baz"bat'];
        $html    = '<script>foo'; // should not escape: return raw html
        $subject->setRenderHtml(fn(array $row): string => $row[$id] . $html);
        $render = new HtmlRender();
        self::assertEquals($row[$id] . $html, $subject->renderCellHtml($render, $row));
    }
}
