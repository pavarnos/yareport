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

class ReportTest extends TestCase
{
    public function testEmpty(): void
    {
        $subject = new Report();
        self::assertEquals([], $subject->getVisibleColumns());
        self::assertEquals([], $subject->getAllColumns());
        self::assertEquals([], $subject->getDefaultSortOrder());
        self::assertEquals([], $subject->getSortOrder(new Sorter()));
        self::assertFalse($subject->hasOptionalColumns());
        self::assertFalse($subject->isVisibleColumn('no-such'));
    }

    public function testAddColumn(): void
    {
        $subject    = new Report();
        $testColumn = new Column($id = 'name', 'title');
        self::assertEquals($testColumn, $subject->addColumn($testColumn));
        self::assertTrue($subject->isVisibleColumn($id));
        self::assertEquals([$id => $testColumn], $subject->getVisibleColumns());
        self::assertEquals([$id => $testColumn], $subject->getAllColumns());
        self::assertEquals($testColumn, $subject->getColumn($id));
        self::assertFalse($subject->hasOptionalColumns());
    }

    public function testAddOptionalColumn(): void
    {
        $subject    = new Report();
        $testColumn = new Column($id = 'name', 'title');
        self::assertEquals($testColumn, $subject->addOptionalColumn($testColumn));
        self::assertFalse($subject->isVisibleColumn($id));
        self::assertEquals([], $subject->getVisibleColumns());
        self::assertEquals([$id => $testColumn], $subject->getAllColumns());
        self::assertEquals($testColumn, $subject->getColumn($id));
        self::assertTrue($subject->hasOptionalColumns());
    }

    public function testAddOptionalRequiredColumn(): void
    {
        $subject    = new Report();
        $testColumn = (new Column('name', 'title'))->setIsRequired();
        $this->expectException(\AssertionError::class); // must always be visible
        $subject->addOptionalColumn($testColumn);
    }

    public function testAddColumn_Twice(): void
    {
        // adding two columns with the same id overwrites the first
        $subject = new Report();
        $one     = new Column($id = 'name', 'title 1');
        self::assertEquals($one, $subject->addColumn($one));
        self::assertEquals($one, $subject->getColumn($id));

        $two = new Column($id, 'title 2');
        self::assertEquals($two, $subject->addColumn($two));
        self::assertEquals([$id => $two], $subject->getVisibleColumns());
        self::assertEquals([$id => $two], $subject->getAllColumns());
        self::assertEquals($two, $subject->getColumn($id));
    }

    public function testGetUnknownColumn(): void
    {
        $subject = new Report();
        $this->expectException(ReportException::class);
        $subject->getColumn('no_such_column');
    }

    public function testSortOrder(): void
    {
        $subject = new Report();
        self::assertEquals([], $subject->getDefaultSortOrder(), 'no columns to sort by');

        $nameColumn      = (new Column('person_name', 'title1'))->setSortOrder(['person_name']);
        $birthDateColumn = (new Column('birth_date', 'title'))->setSortOrder(['birth_date']);
        $subject->addColumn($nameColumn);
        $subject->addColumn($birthDateColumn);
        self::assertEquals($nameColumn->getAscending(), $subject->getDefaultSortOrder());
        self::assertEquals($nameColumn->getAscending(), $subject->getSortOrder(new Sorter()));
        self::assertEquals($nameColumn->getAscending(), $subject->getSortOrder(new Sorter('person_name')));
        self::assertEquals($birthDateColumn->getAscending(), $subject->getSortOrder(new Sorter('birth_date')));
    }
}
