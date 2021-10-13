<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class ShortenedColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): ShortenedColumn
    {
        return new ShortenedColumn($id, $title, $description);
    }

    /**
     * @param int    $length
     * @param string $expected
     * @dataProvider getShorter
     */
    public function testRenderCellHtmlShorter(int $length, string $expected): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setMaxLength($length);
        $row    = ['foo' => 'bar', $id => '1234567890'];
        $render = new HtmlRender();
        self::assertEquals($expected, $subject->renderCellHtml($render, $row));
    }

    public function getShorter(): array
    {
        return [
            'len 4'  => [4, '1...'],
            'len 5'  => [5, '12...'],
            'len 6'  => [6, '123...'],
            'len 7'  => [7, '1234...'],
            'len 8'  => [8, '12345...'],
            'len 9'  => [9, '123456...'],
            'len 10' => [10, '1234567890'],
            'len 11' => [11, '1234567890'],
            'len 99' => [99, '1234567890'],
        ];
    }
}
