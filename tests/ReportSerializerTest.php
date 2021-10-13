<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport;

use LSS\YAReport\Column\Column;
use PHPUnit\Framework\TestCase;

class ReportSerializerTest extends TestCase
{
    public function testEmpty(): void
    {
        $report  = new Report();
        $subject = new ReportSerializer();
        self::assertEquals($report, $subject->fromJson($report, $subject->toJson($report)));
    }

    public function testRequiredAndOptionalColumns(): void
    {
        $template = new Report();
        $template->addColumn($one = new Column('one', 'One'));
        $template->addOptionalColumn($two = new Column('two', 'Two'));
        $template->addColumn($three = new Column('three', 'Three'));
        $template->addColumn($four = new Column('four', 'Four'))->setIsRequired();
        $subject = new ReportSerializer();
        $result  = $subject->fromArray($template,
                                       [
                                           ['id' => 'three', 'title' => $newTitle = 'Three3'],
                                           ['id' => 'no-such', 'title' => 'Ignored'],
                                       ]
        );
        $three3  = clone ($three)->setTitle($newTitle); // title is changed by config

        // column four is required by the template, so it is added to the end of the visible columns
        self::assertEquals(['three' => $three3, 'four' => $four], $result->getVisibleColumns());

        // all columns in the template must be in the final report, optional ones are not visible
        self::assertEquals(
            ['three' => $three3, 'four' => $four, 'one' => $one, 'two' => $two],
            $result->getAllColumns()
        );

        // on subsequent save, the required column 'four' is included and only the visible columns are saved
        self::assertEquals(
            [['id' => 'three', 'title' => 'Three3'], ['id' => 'four', 'title' => 'Four']],
            $subject->toArray($result)
        );
    }
}
