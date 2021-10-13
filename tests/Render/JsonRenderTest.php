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
use PHPUnit\Framework\TestCase;

class JsonRenderTest extends TestCase
{
    public function testRenderEmpty(): void
    {
        $result = (new JsonRender())->render($this->getReport(), []);
        self::assertEquals($this->getMeta(), $result['meta']);
        self::assertEquals([], $result['data']);
    }

    public function testRender(): void
    {
        $rawData  = [
            ['name' => 'Fred', 'email' => 'fred@example.com', 'rate' => '25.51', 'weight' => '99', 'is_active' => '1'],
            ['name' => 'Blank'], // blank row
            ['name' => 'Dave', 'email' => 'dave@example.com', 'rate' => '19', 'weight' => '88', 'is_active' => '0'],
        ];
        $expected = [
            ['name' => 'Fred', 'email' => 'fred@example.com', 'rate' => 25.51, 'weight' => 99, 'is_active' => true],
            ['name' => 'Blank', 'email' => null, 'rate' => null, 'weight' => null, 'is_active' => null],
            ['name' => 'Dave', 'email' => 'dave@example.com', 'rate' => 19, 'weight' => 88, 'is_active' => false],
        ];
        $result   = (new JsonRender())->render($this->getReport(), $rawData);
        self::assertEquals($this->getMeta(), $result['meta']);
        self::assertEquals($expected, $result['data']);
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

    private function getMeta(): array
    {
        return [
            'name'      => ['title' => 'Name', 'type' => 'String', 'description' => 'Personal name'],
            'email'     => ['title' => 'Email', 'type' => 'Email', 'description' => ''],
            'rate'      => ['title' => 'Hourly Rate', 'type' => 'Money', 'description' => ''],
            'weight'    => ['title' => 'Weight (kg)', 'type' => 'Integer', 'description' => 'before eating'],
            'is_active' => ['title' => 'Is Active', 'type' => 'Boolean', 'description' => ''],
        ];
    }
}
