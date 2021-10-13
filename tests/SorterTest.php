<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport;

use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    public function testSetColumn(): void
    {
        $subject = new Sorter();
        $id      = 'my_column';
        $id2     = 'my_column2';

        self::assertEquals(null, $subject->getColumn());

        $subject->setColumn($id2);
        self::assertEquals($id2, $subject->getColumn());

        $subject->setColumn($id);
        self::assertEquals($id, $subject->getColumn());
    }

    public function testAscendingDescending(): void
    {
        $subject = new Sorter();
        self::assertEquals(null, $subject->getColumn());
        self::assertTrue($subject->getIsAscending());

        $id = 'my_column';
        $subject->setColumn($id);
        self::assertTrue($subject->getIsAscending());
        $subject->setColumn($id);
        self::assertFalse($subject->getIsAscending()); // should toggle ascending mode
        $subject->setColumn($id);
        self::assertTrue($subject->getIsAscending()); // should toggle ascending mode
        $subject->setColumn($id);
        self::assertFalse($subject->getIsAscending()); // should toggle ascending mode

        $id2 = 'my_column2';
        $subject->setColumn($id2);
        self::assertEquals($id2, $subject->getColumn());
        self::assertTrue($subject->getIsAscending()); // should flip back to ascending
    }

    public function testSerialize(): void
    {
        $subject = new Sorter($column = 'foo');
        $subject->setColumn($column);
        self::assertEquals($subject, Sorter::fromArray($subject->toArray()));
    }
}
