<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class DateTimeColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): DateTimeColumn
    {
        return new DateTimeColumn($id, $title, $description);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => $date = '2021-01-02 13:04'];
        $render  = new HtmlRender();
        self::assertEquals('2021-01-02 13:04:00', $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlTimezone(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setTimeZone('pacific/auckland');
        $subject->setFormat('d M Y H:i:s');
        $render = new HtmlRender();
        self::assertEquals(
            '03 Jan 2021 02:04:05',
            $subject->renderCellHtml($render, ['foo' => 'bar', $id => '2021-01-02 13:04:05'])
        );
    }

}
