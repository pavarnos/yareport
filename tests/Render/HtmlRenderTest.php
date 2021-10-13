<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   13 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Column\BooleanColumn;
use LSS\YAReport\Column\CalculatedColumn;
use LSS\YAReport\Column\Column;
use LSS\YAReport\Column\EmailColumn;
use LSS\YAReport\Column\IntegerColumn;
use LSS\YAReport\Column\MoneyColumn;
use LSS\YAReport\Report;
use LSS\YAReport\Sorter;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class HtmlRenderTest extends TestCase
{
    public function testRenderEmpty(): void
    {
        $result = (new HtmlRender())->render($this->getReport(), [], new Sorter())->__toString();
        self::assertEquals('', $result);
    }

    public function testRenderUnsorted(): void
    {
        $data = [
            ['name' => 'Fred', 'email' => 'fred@example.com', 'rate' => '25.51', 'weight' => '99', 'is_active' => '1'],
            ['name' => 'Blank'], // blank row
            ['name' => 'Dave', 'email' => 'dave@example.com', 'rate' => '19', 'weight' => '88', 'is_active' => '0'],
        ];
        $html = (new HtmlRender())->render($this->getReport(), $data, new Sorter())->__toString();
        self::assertStringContainsString('<thead><tr>', $html);
        self::assertStringContainsString('<th>Name</th>', $html);
        self::assertStringContainsString('<th>Email</th>', $html);
        self::assertStringContainsString('<th class="text-right">Hourly Rate</th>', $html);
        self::assertStringContainsString('<th class="text-right">Weight (kg)</th>', $html);
        self::assertStringContainsString('<th>Is Active</th>', $html);
        self::assertStringContainsString('<th></th>', $html);
        self::assertStringContainsString('</tr></thead>', $html);
        self::assertEquals(count($data), substr_count($html, '*menu*'));
        foreach ($data as $row) {
            self::assertStringContainsString($row['name'], $html);
            self::assertStringContainsString($row['email'] ?? '', $html);
            self::assertStringContainsString('$' . ($row['rate'] ?? ''), $html);
            self::assertStringContainsString($row['weight'] ?? '', $html);
            self::assertStringContainsString(empty($row['is_active']) ? 'Nope' : 'Yep', $html);
        }
    }

    public function testRenderSorted(): void
    {
        $data    = [
            ['name' => 'Fred', 'email' => 'fred@example.com', 'rate' => '25.51', 'weight' => '99', 'is_active' => '1'],
            ['name' => 'Blank'], // blank row
            ['name' => 'Dave', 'email' => 'dave@example.com', 'rate' => '19', 'weight' => '88', 'is_active' => '0'],
        ];
        $subject = new HtmlRender();
        $subject->setSortedAscendingSymbol('*Up*')
                ->setSortedDescendingSymbol('*Down*')
                ->setSortedByColumnStyle('sorted-by')
                ->setUrlSortParameter('sort')
                ->setRightAlignStyle('right-align');
        $html = $subject->render($this->getReport(), $data, new Sorter('rate'), new Uri('/foo?bar=baz'))->__toString();
        // sorting-by is on the correct column and correctly appended to column styles,
        // *Up* indicator is shown
        // sort url is correctly formed
        self::assertStringContainsString('<thead><tr>', $html);
        self::assertStringContainsString(
            '<th><a href="/foo?bar=baz&sort=name" title="Personal name">Name</a></th>',
            $html
        );
        self::assertStringContainsString('<th><a href="/foo?bar=baz&sort=email">Email</a></th>', $html);
        self::assertStringContainsString(
            '<th class="right-align sorted-by"><a href="/foo?bar=baz&sort=rate">Hourly Rate*Up*</a></th>',
            $html
        );
        self::assertStringContainsString('<th class="right-align">Weight (kg)</th>', $html);
        self::assertStringContainsString('<th>Is Active</th>', $html);
        self::assertStringContainsString('<th></th>', $html);
        self::assertStringContainsString('</tr></thead>', $html);
    }

    private function getReport(): Report
    {
        $report = new Report();
        $report->addColumn((new Column('name', 'Name', 'Personal name'))->setSortOrder(['name']));
        $report->addColumn((new EmailColumn('email', 'Email'))->setSortOrder(['name']));
        $report->addColumn(
            (new MoneyColumn('rate', 'Hourly Rate'))->setCurrencySymbol('$')->setSortOrder(['rate', 'name'])
        );
        $report->addColumn(new IntegerColumn('weight', 'Weight (kg)', 'before eating'));
        $report->addColumn((new BooleanColumn('is_active', 'Is Active'))->setNoHtml('Nope')->setYesHtml('Yep'));
        $report->addColumn((new CalculatedColumn('menu', ''))->setRenderHtml(fn() => '*menu*'));
        return $report;
    }
}
