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

class CalculatedRowStyleTest extends TestCase
{
    public function testRender(): void
    {
        $one     = 'style-one';
        $two     = 'style-two';
        $subject = new HtmlRender();
        $subject->addRowDecorator(
            new CalculatedRowStyle(fn(array $row): string => match ($row['value'] ?? '') {
                1, '1' => $one,
                2, '2' => $two,
                default => ''
            })
        );
        $data = [
            ['name' => 'First', 'value' => 5],
            ['name' => 'Second'],
            ['name' => 'Third', 'value' => 2],
            ['name' => 'Fourth', 'value' => 1],
        ];
        $html = $subject->render($this->getReport(), $data, new Sorter())->__toString();
        self::assertStringContainsString('<tr><td>First</td>', $html);
        self::assertStringContainsString('<tr><td>Second</td>', $html);
        self::assertStringContainsString('<tr class="' . $two . '"><td>Third</td>', $html);
        self::assertStringContainsString('<tr class="' . $one . '"><td>Fourth</td>', $html);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name'));
        $report->addColumn(new Column('value', 'Value'));
        return $report;
    }
}
