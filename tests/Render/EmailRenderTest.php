<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Column\Column;
use LSS\YAReport\Column\EmailColumn;
use LSS\YAReport\Report;
use PHPUnit\Framework\TestCase;

class EmailRenderTest extends TestCase
{
    public function testRenderEmpty(): void
    {
        $result = (new EmailRender())->render($this->getReport(), []);
        self::assertEquals([], $result);
    }

    public function testRender(): void
    {
        $rawData = [
            ['name' => 'Fred', 'email1' => 'fred@example.com', 'email2' => ''],
            ['name' => '', 'email2' => 'bar@example.com'],
            ['name' => 'Blank'],
            ['name' => 'Dave', 'email2' => 'dave@example.com'],
        ];
        $expected = ['Fred <fred@example.com>', 'bar@example.com', 'Dave <dave@example.com>',];
        $result = (new EmailRender())->render(
            $this->getReport(),
            $rawData,
            fn(string $columnId, array $row): string => $row['name'] ?? ''
        );
        self::assertEquals($expected, $result);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name', 'Personal name'));
        $report->addColumn(new EmailColumn('email1', 'Email'));
        $report->addColumn(new EmailColumn('email2', 'Email 2'));
        return $report;
    }
}
