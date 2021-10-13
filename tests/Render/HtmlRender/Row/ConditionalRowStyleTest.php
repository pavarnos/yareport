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

class ConditionalRowStyleTest extends TestCase
{
    public function testRender(): void
    {
        $subject = new HtmlRender();
        $subject->addRowDecorator(
            new ConditionalRowStyle($style = 'selected', fn(array $row): bool => ($row['value'] ?? 0) > 10)
        );
        $data = [
            ['name' => 'First', 'value' => 5],
            ['name' => 'Second'],
            ['name' => 'Third', 'value' => 11],
            ['name' => 'Fourth', 'value' => 3],
        ];
        $html = $subject->render($this->getReport(), $data, new Sorter())->__toString();
        self::assertStringContainsString('<tr><td>First</td>', $html);
        self::assertStringContainsString('<tr><td>Second</td>', $html);
        self::assertStringContainsString('<tr class="selected"><td>Third</td>', $html);
        self::assertStringContainsString('<tr><td>Fourth</td>', $html);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name'));
        $report->addColumn(new Column('value', 'Value'));
        return $report;
    }
}
