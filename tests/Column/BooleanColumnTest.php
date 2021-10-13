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

class BooleanColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): BooleanColumn
    {
        return new BooleanColumn($id, $title, $description);
    }

    public function testRenderCell(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => true];
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendBoolean')->with($row[$id]);
        $subject->renderCell($render, $row);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setNoHtml($no = 'No way dude');
        $subject->setYesHtml($yes = 'Ya bro');
        $render = new HtmlRender();
        self::assertEquals($yes, $subject->renderCellHtml($render, ['foo' => 'bar', $id => true]));
        self::assertEquals($no, $subject->renderCellHtml($render, ['foo' => 'bar', $id => false]));
    }
}
