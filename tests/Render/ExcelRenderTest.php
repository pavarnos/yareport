<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Column\BooleanColumn;
use LSS\YAReport\Column\Column;
use LSS\YAReport\Column\EmailColumn;
use LSS\YAReport\Column\IntegerColumn;
use LSS\YAReport\Column\MoneyColumn;
use LSS\YAReport\Report;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PHPUnit\Framework\TestCase;

class ExcelRenderTest extends TestCase
{
    public function testRenderEmpty(): void
    {
        $sheet = (new ExcelRender())->render($this->getReport(), [])->getActiveSheet();
        $this->assertValidHeader($sheet);
    }

    public function testRender(): void
    {
        $rawData  = [
            ['name' => 'Fred', 'email' => 'fred@example.com', 'rate' => '25.51', 'weight' => '99', 'is_active' => '1'],
            ['name' => 'Blank'], // blank row
            ['name' => 'Dave', 'email' => 'dave@example.com', 'rate' => '19', 'weight' => '88', 'is_active' => '0'],
        ];
        $expected = [
            ['Fred', 'fred@example.com', 25.51, 99, true],
            ['Blank', '', '', '', '', ''],
            ['Dave', 'dave@example.com', 19, 88, false],
        ];
        $subject  = new ExcelRender();
        $subject->setDocumentTitle($title = 'Foo');
        $sheet = $subject->render($this->getReport(), $rawData)->getActiveSheet();
        $this->assertValidHeader($sheet);
        foreach ($expected as $rowIndex => $row) {
            foreach ($row as $columnIndex => $cell) {
                self::assertEquals($cell, $sheet->getCellByColumnAndRow($columnIndex + 1, $rowIndex + 2)->getValue());
            }
        }
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name', 'Personal name'));
        $report->addColumn(new EmailColumn('email', 'Email'));
        $report->addColumn(new MoneyColumn('rate', 'Hourly Rate'));
        $report->addColumn(new IntegerColumn('weight', 'Weight (kg)', 'before eating'));
        $report->addColumn(new BooleanColumn('is_active', 'Is Active'));
        return $report;
    }

    private function assertValidHeader(Worksheet $sheet): void
    {
        self::assertEquals('Name', $sheet->getCell('A1')->getValue());
    }
}
