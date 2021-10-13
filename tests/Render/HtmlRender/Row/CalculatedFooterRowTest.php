<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   14 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender\Row;

use LSS\YAReport\Column\Column;
use LSS\YAReport\Render\HtmlRender;
use LSS\YAReport\Report;
use LSS\YAReport\Sorter;
use PHPUnit\Framework\TestCase;

class CalculatedFooterRowTest extends TestCase
{
    public function testRender(): void
    {
        $subject = new HtmlRender();
        $subject->addRowDecorator(new CalculatedFooterRow(fn(Column $column): string => $column->getID() . '-footer'));
        $data = [
            ['name' => 'First', 'value' => 5],
            ['name' => 'Second'],
        ];
        $html = $subject->render($this->getReport(), $data, new Sorter())->__toString();
        self::assertStringContainsString('<tr><td>name-footer</td><td>value-footer</td></tr>', $html);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name'));
        $report->addColumn(new Column('value', 'Value'));
        return $report;
    }
}
