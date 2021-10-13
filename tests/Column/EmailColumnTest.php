<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class EmailColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): EmailColumn
    {
        return new EmailColumn($id, $title, $description);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => $email = 'foo@example.com'];
        $render  = new HtmlRender();
        self::assertStringContainsString('mailto:' . $email, $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlEmpty(): void
    {
        $subject = $this->getSubject('thing');
        self::assertEquals('', $subject->renderCellHtml(new HtmlRender(), []));
    }
}
