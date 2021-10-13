<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   14 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render\HtmlRender;

use PHPUnit\Framework\TestCase;

class TableElementTest extends TestCase
{
    public function testEmpty(): void
    {
        self::assertEquals('<td></td>', (new TableElement('td'))->__toString());
        self::assertEquals('<td></td>', (new TableElement('td', true))->__toString());
        self::assertEquals('', (new TableElement('td', false))->__toString());
    }

    public function testIsEmpty(): void
    {
        $subject = new TableElement('td');
        self::assertTrue($subject->isEmpty());

        $subject->setContent('foo');
        self::assertFalse($subject->isEmpty());

        $subject->setContent('');
        self::assertTrue($subject->isEmpty());

        $subject->addStyle('bar');
        $subject->setPrefix('bat');
        $subject->setSuffix('baz');
        $subject->setAttribute('id', 'dong');
        self::assertTrue($subject->isEmpty());
    }

    public function testAddStyles(): void
    {
        $subject = new TableElement('td');
        $subject->addStyle('one');
        $subject->addStyle('two');
        $subject->addStyle('one'); // adding twice should be ignored
        $subject->addStyles(['three', 'two', 'four']);
        $subject->addStyleIf(false, 'five');
        $subject->addStyleIf(true, 'six');
        $subject->addStyle('one');
        self::assertEquals('<td class="one two three four six"></td>', $subject->__toString());
    }

    public function testAttribute(): void
    {
        $subject = new TableElement('td');
        $subject->setAttribute('id', 'my-id');
        $subject->setAttribute('colspan', '3');
        self::assertEquals('<td id="my-id" colspan="3"></td>', $subject->__toString());
    }

    public function testPrefix(): void
    {
        $subject = new TableElement('td');
        $subject->setContent($content = 'bar')
                ->setPrefix($prefix = 'before');
        self::assertEquals($prefix, $subject->getPrefix());
        self::assertEquals($prefix . '<td>' . $content . '</td>', $subject->__toString());
    }

    public function testSuffix(): void
    {
        $subject = new TableElement('td');
        $subject->setContent($content = 'bar')
                ->setSuffix($suffix = 'after');
        self::assertEquals($suffix, $subject->getSuffix());
        self::assertEquals('<td>' . $content . '</td>' . $suffix, $subject->__toString());
    }
}
