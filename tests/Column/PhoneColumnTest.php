<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class PhoneColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): PhoneColumn
    {
        return new PhoneColumn($id, $title, $description);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => $number = '+64-21-234 5678'];
        $render  = new HtmlRender();
        self::assertStringContainsString('tel:' . urlencode($number), $subject->renderCellHtml($render, $row));
        self::assertStringContainsString($render->escape($number), $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlEmpty(): void
    {
        $subject = $this->getSubject('thing');
        self::assertEquals('', $subject->renderCellHtml(new HtmlRender(), []));
    }
}
