<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class DateColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): DateColumn
    {
        return new DateColumn($id, $title, $description);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => '2021-01-02 13:04'];
        $render  = new HtmlRender();
        self::assertEquals('2021-01-02', $subject->renderCellHtml($render, $row), 'same day');
    }

    public function testRenderCellHtmlEmpty(): void
    {
        $subject = $this->getSubject('thing');
        self::assertEquals('', $subject->renderCellHtml(new HtmlRender(), []));
    }

    public function testRenderCellHtmlTimezone(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setTimeZone('pacific/auckland');
        $subject->setFormat('d M Y');
        $render = new HtmlRender();
        self::assertEquals(
            '03 Jan 2021',
            $subject->renderCellHtml($render, ['foo' => 'bar', $id => '2021-01-02 13:04']),
            'next day because it is after 12nn UTC and pacific/auckland is 12 hours away'
        );
    }
}
