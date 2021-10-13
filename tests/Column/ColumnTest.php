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
use LSS\YAReport\ReportException;
use PHPUnit\Framework\TestCase;

class ColumnTest extends TestCase
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): Column
    {
        return new Column($id, $title, $description);
    }

    public function testConstructor(): void
    {
        $subject = $this->getSubject($id = 'my_id', $title = 'A title', $description = 'some text');
        self::assertEquals($id, $subject->getID());
        self::assertEquals($title, $subject->getTitle());
        self::assertEquals($description, $subject->getDescription());

        $subject->setTitle($title2 = 'new title');
        self::assertEquals($title2, $subject->getTitle());
    }

    public function testIsRequired(): void
    {
        $subject = $this->getSubject();
        self::assertFalse($subject->isRequired());
        self::assertEquals($subject, $subject->setIsRequired());
        self::assertTrue($subject->isRequired());
    }

    public function testRightAlign(): void
    {
        $subject = $this->getSubject();
        self::assertFalse($subject->isRightAligned());
        self::assertEquals($subject, $subject->setRightAlign());
        self::assertTrue($subject->isRightAligned());
    }

    public function testCSSStyle(): void
    {
        $subject = $this->getSubject();
        self::assertEquals([], $subject->getCSSStyles());
        self::assertEquals($subject, $subject->setRightAlign());
        self::assertEquals($subject, $subject->addCSSStyle($style1 = 'xyz'));
        self::assertEquals([$style1], $subject->getCSSStyles());
        self::assertEquals($subject, $subject->addCSSStyle($style1));
        self::assertEquals($subject, $subject->addCSSStyle($style2 = 'style2'));
        self::assertEquals([$style1, $style2], $subject->getCSSStyles());
    }

    public function testSetSortOrder_DisableAscending(): void
    {
        $subject = $this->getSubject();
//        $this->expectException(ReportException::class);
        self::assertEquals([], $subject->getAscending());
    }

    public function testSetSortOrder_FullyPopulated(): void
    {
        $asc     = ['one', 'two'];
        $desc    = ['three', 'four'];
        $subject = $this->getSubject();
        $subject->setSortOrder($asc, $desc);
        self::assertEquals($asc, $subject->getAscending());
        self::assertEquals($desc, $subject->getDescending());
    }

    public function testSortOrder_DescendingAuto(): void
    {
        $asc     = ['one', 'two'];
        $subject = $this->getSubject();
        $subject->setSortOrder($asc);
        self::assertEquals($asc, $subject->getAscending());
        self::assertEquals(['one DESC', 'two DESC'], $subject->getDescending());
    }

    public function testSortOrder_DescendingDisabled(): void
    {
        $asc     = ['one', 'two'];
        $subject = $this->getSubject();
        $subject->setSortOrder($asc, []);
        self::assertEquals($asc, $subject->getAscending());
        try {
            $subject->getDescending();
            self::fail('expected exception');
        } catch (ReportException) {
        }
    }

    public function testRenderTitle(): void
    {
        $subject = $this->getSubject('id', $title = 'the title');
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendString')->with($title);
        $subject->renderTitle($render);
    }

    public function testRenderCell(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 'baz'];
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendString')->with($row[$id]);
        $subject->renderCell($render, $row);
    }

    public function testRenderCellBlank(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $render  = $this->createMock(RenderInterface::class);
        $render->expects(self::once())->method('appendBlank');
        $subject->renderCell($render, ['foo' => 'bar', $id => null]);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 'baz"bat'];
        $render  = new HtmlRender();
        self::assertEquals($render->escape($row[$id]), $subject->renderCellHtml($render, $row));
    }
}
