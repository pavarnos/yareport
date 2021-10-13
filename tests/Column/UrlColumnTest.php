<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class UrlColumnTest extends ColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): UrlColumn
    {
        return new UrlColumn($id, $title, $description);
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => ''];
        $render  = new HtmlRender();
        self::assertEquals('', $subject->renderCellHtml($render, $row));
    }

    /**
     * @param string $url
     * @param string $expected
     * @param bool   $isExternal
     * @dataProvider getVariants
     */
    public function testRenderCellHtmlVariants(string $url, string $expected, bool $isExternal): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => $url];
        $render  = new HtmlRender();
        $actual  = $subject->renderCellHtml($render, $row);
        self::assertStringContainsString('href="' . $expected . '"', $actual);
        self::assertStringContainsString('>' . $expected . '<', $actual);
        if ($isExternal) {
            self::assertStringContainsString(UrlColumn::EXTERNAL_LINK_ATTRIBUTES, $actual);
        } else {
            self::assertStringNotContainsString(UrlColumn::EXTERNAL_LINK_ATTRIBUTES, $actual);
        }
    }

    public function getVariants(): array
    {
        return [
            'local'   => ['/some/value', '/some/value', false],
            'no http' => ['www.example.com/some/value', 'https://www.example.com/some/value', true],
            'http'    => ['http://www.example.com/some/value', 'http://www.example.com/some/value', true],
            'https'   => ['https://www.example.com/some/value', 'https://www.example.com/some/value', true],
        ];
    }
}
