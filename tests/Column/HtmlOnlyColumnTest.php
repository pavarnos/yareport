<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\RenderInterface;

class HtmlOnlyColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): HtmlOnlyColumn
    {
        return new HtmlOnlyColumn($id, $title, $description);
    }

    public function testRenderTitle(): void
    {
        $subject = $this->getSubject();
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::never())->method('appendString');
        $subject->renderTitle($render);
    }

    public function testRenderCell(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::never())->method('appendString');
        $subject->renderCell($render, ['foo' => 'bar', $id => 'baz']);
    }

    public function testRenderCellBlank(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::never())->method('appendBlank');
        $subject->renderCell($render, ['foo' => 'bar', $id => null]);
    }
}
