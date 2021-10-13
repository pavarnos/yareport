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

class FloatColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): FloatColumn
    {
        return new FloatColumn($id, $title, $description);
    }

    public function testRightAlign(): void
    {
        $subject = $this->getSubject();
        self::assertTrue($subject->isRightAligned());
    }

    public function testRenderCell(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 123.4567];
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendFloat')->with($row[$id]);
        $subject->renderCell($render, $row);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 123.4567];
        $render  = new HtmlRender();
        self::assertEquals($row[$id], $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlDecimalPlaces(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setDecimalPlaces(6);
        $row    = ['foo' => 'bar', $id => 123.4567];
        $render = new HtmlRender();
        self::assertEquals('123.456700', $subject->renderCellHtml($render, $row));
    }
}
