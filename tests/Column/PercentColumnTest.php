<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class PercentColumnTest extends FloatColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): PercentColumn
    {
        return new PercentColumn($id, $title, $description);
    }

    public function testRightAlign(): void
    {
        $subject = $this->getSubject();
        self::assertTrue($subject->isRightAligned());
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 23.4567];
        $render  = new HtmlRender();
        self::assertEquals('23.4567%', $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlDecimalPlaces(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setDecimalPlaces(0);
        $row    = ['foo' => 'bar', $id => 23.4567];
        $render = new HtmlRender();
        self::assertEquals('23%', $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlEmpty(): void
    {
        $subject = $this->getSubject('thing');
        self::assertEquals('', $subject->renderCellHtml(new HtmlRender(), []));
    }
}
