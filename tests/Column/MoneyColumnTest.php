<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   12 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRender;

class MoneyColumnTest extends FloatColumnTest
{
    public function getSubject(string $id = 'a', string $title = 'b', string $description = ''): MoneyColumn
    {
        return new MoneyColumn($id, $title, $description);
    }

    public function testRightAlign(): void
    {
        $subject = $this->getSubject();
        self::assertTrue($subject->isRightAligned());
    }

    public function testRenderCellHtml(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $row     = ['foo' => 'bar', $id => 1234.5678];
        $render  = new HtmlRender();
        self::assertEquals('1,234.57', $subject->renderCellHtml($render, $row));
    }

    public function testRenderCellHtmlSymbol(): void
    {
        $subject = $this->getSubject($id = 'thing');
        $subject->setCurrencySymbol($currencySymbol = 'USD')
                ->setDecimalPlaces(3);
        $row    = ['foo' => 'bar', $id => 1234.5678];
        $render = new HtmlRender();
        self::assertEquals($currencySymbol . '1,234.568', $subject->renderCellHtml($render, $row));
    }
}
