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
use LSS\YAReport\Column\DateTimeColumn;
use LSS\YAReport\Column\EmailColumn;
use LSS\YAReport\Column\IntegerColumn;
use LSS\YAReport\Column\MoneyColumn;
use LSS\YAReport\Report;
use PHPUnit\Framework\TestCase;

class CSVRenderTest extends TestCase
{
    public function testRenderEmpty(): void
    {
        $result = (new CSVRender())->render($this->getReport(), []);
        self::assertEquals($this->getTitles() . PHP_EOL, $result);
    }

    public function testRender(): void
    {
        $rawData  = [
            [
                'name'       => 'Fred',
                'email'      => 'fred@example.com',
                'rate'       => '25.51',
                'weight'     => '99',
                'is_active'  => '1',
                'last_login' => '5 Jan 2021',
            ],
            ['name' => 'Blank'], // blank row
            [
                'name'       => 'Dave',
                'email'      => 'dave@example.com',
                'rate'       => '19',
                'weight'     => '88',
                'is_active'  => '0',
                'last_login' => '2021-01-02T03:04:05+00:00',
            ],
        ];
        $expected = [
            $this->getTitles(),
            '"Fred","fred@example.com",25.51,99,1,"5 Jan 2021"',
            '"Blank",,,,,',
            '"Dave","dave@example.com",19,88,0,"2021-01-02T03:04:05+00:00"',
        ];
        $result   = (new CSVRender())->render($this->getReport(), $rawData);
        self::assertEquals(join(PHP_EOL, $expected) . PHP_EOL, $result);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn(new Column('name', 'Name', 'Personal name'));
        $report->addColumn(new EmailColumn('email', 'Email'));
        $report->addColumn(new MoneyColumn('rate', 'Hourly Rate'));
        $report->addColumn(new IntegerColumn('weight', 'Weight (kg)', 'before eating'));
        $report->addColumn(new BooleanColumn('is_active', 'Is Active'));
        $report->addColumn(new DateTimeColumn('last_login', 'Last Login'));
        return $report;
    }

    private function getTitles(): string
    {
        return '"Name","Email","Hourly Rate","Weight (kg)","Is Active","Last Login"';
    }
}
