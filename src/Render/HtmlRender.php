<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   02 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Column\Column;
use LSS\YAReport\Render\HtmlRender\Row\RowDecoratorInterface;
use LSS\YAReport\Render\HtmlRender\TableElement;
use LSS\YAReport\Report;
use LSS\YAReport\Sorter;
use Psr\Http\Message\UriInterface;

/**
 * render a report as an html table
 *
 * There are lots of protected methods in this class to help you create sub-classes that change the standard behaviour.
 * I'm not making any backwards compatibility guarantees re the interface, but changes here will likely be small and
 * easily migrated (because I've used this library for many years across many projects and it is reasonably stable).
 * - Prefer using the RowDecorators where you can.
 * - If you have a use-case for cell decorators, lets talk.
 * - There is no need for a table decorator because the render() function returns a TableElement which you can tweak
 *   before rendering eg to add paginators, item counts, menus etc
 */
class HtmlRender implements HtmlRenderInterface
{
    protected string $rightAlign = 'text-right'; // a bootstrap style for column->isRightAligned()

    protected string $sortedByColumn = 'sorting-by'; // a css style name

    protected string $sortParameter = 'sort';

    protected string $sortedAscendingSymbol = '&#129045;';

    protected string $sortedDescendingSymbol = '&#129047;';

    /** @var RowDecoratorInterface[] */
    protected array $rowDecorators = [];

    /**
     * @param Report        $report
     * @param iterable      $data    an array of rows: usually from a database query
     * @param Sorter        $sorter
     * @param ?UriInterface $baseUrl so the title row can allow click to sort: see $this->sortParameter
     * @return TableElement
     */
    public function render(Report $report, iterable $data, Sorter $sorter, ?UriInterface $baseUrl = null): TableElement
    {
        if (empty($data)) {
            return new TableElement('table', false);
        }
        $columns = $report->getVisibleColumns();
        $output  = $this->columnTitleRow($columns, $sorter, $baseUrl);
        $output  .= $this->beforeFirstRow($columns);
        $output  .= $this->tableBody($columns, $data);
        $output  .= $this->afterLastRow($columns);
        return (new TableElement('table'))->setContent($output);
    }

    public function escape(string $text): string
    {
        if ($text === '') {
            return '';
        }
        // see https://paragonie.com/blog/2015/06/preventing-xss-vulnerabilities-in-php-everything-you-need-know
        return htmlspecialchars(trim($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function addRowDecorator(RowDecoratorInterface $decorator): static
    {
        $this->rowDecorators[] = $decorator;
        return $this;
    }

    public function setRightAlignStyle(string $rightAlign): static
    {
        $this->rightAlign = $rightAlign;
        return $this;
    }

    public function setSortedByColumnStyle(string $sortedByColumn): static
    {
        $this->sortedByColumn = $sortedByColumn;
        return $this;
    }

    public function setUrlSortParameter(string $sortParameter): static
    {
        $this->sortParameter = $sortParameter;
        return $this;
    }

    public function setSortedAscendingSymbol(string $sortedAscendingSymbol): static
    {
        $this->sortedAscendingSymbol = $sortedAscendingSymbol;
        return $this;
    }

    public function setSortedDescendingSymbol(string $sortedDescendingSymbol): static
    {
        $this->sortedDescendingSymbol = $sortedDescendingSymbol;
        return $this;
    }

    /**
     * @param Column[] $columns
     * @return string
     */
    protected function beforeFirstRow(array $columns): string
    {
        $output = '';
        foreach ($this->rowDecorators as $decorator) {
            $output .= $decorator->beforeFirstRow($columns);
        }
        return $output;
    }

    /**
     * @param Column[]      $columns
     * @param Sorter        $sorter
     * @param ?UriInterface $baseUrl
     * @return TableElement
     */
    protected function columnTitleRow(array $columns, Sorter $sorter, ?UriInterface $baseUrl = null): TableElement
    {
        $output = '';
        foreach ($columns as $column) {
            $output .= $this->titleCellHtml($column, $sorter, $baseUrl);
        }
        return (new TableElement('tr'))
            ->setPrefix('<thead>')
            ->setSuffix('</thead>')
            ->setContent($output);
    }

    /**
     * render the column title, with links to sort
     * @param Column        $column
     * @param Sorter        $sorter
     * @param ?UriInterface $baseUrl
     * @return TableElement
     */
    protected function titleCellHtml(Column $column, Sorter $sorter, ?UriInterface $baseUrl = null): TableElement
    {
        $cell = (new TableElement('th'))
            ->addStyles($column->getCSSStyles())
            ->addStyleIf($column->isRightAligned(), $this->rightAlign);

        $title = $column->getTitle();
        if (empty($title)) {
            return $cell;
        }

        if (empty($column->getAscending()) || is_null($baseUrl)) {
            // a plain title if we cannot sort by this column or sort by any column at all because baseUrl is null
            return $cell->setContent($this->escape($title));
        }

        $output = '<a href="' . $this->makeSortUri($baseUrl, $column->getID()) . '"';
        if (!empty($column->getDescription())) {
            $output .= ' title="' . $this->escape($column->getDescription()) . '"';
        }
        $output .= '>' . $this->escape($title);
        if ($sorter->getColumn() === $column->getID()) {
            $cell->addStyle($this->sortedByColumn);
            $output .= $sorter->getIsAscending() ? $this->sortedAscendingSymbol : $this->sortedDescendingSymbol;
        }
        $output .= '</a>';
        return $cell->setContent($output);
    }

    protected function makeSortUri(UriInterface $baseUrl, string $columnId): UriInterface
    {
        parse_str($baseUrl->getQuery(), $parts);
        $parts[$this->sortParameter] = $columnId;
        return $baseUrl->withQuery(http_build_query($parts));
    }

    /**
     * you could add cell decorators here similar to the row decorators by overriding this method.
     * @param Column $column
     * @param array  $row
     * @return TableElement
     */
    protected function dataCell(Column $column, array $row): TableElement
    {
        return (new TableElement('td'))
            ->addStyles($column->getCSSStyles())
            ->addStyleIf($column->isRightAligned(), $this->rightAlign)
            ->setContent($column->renderCellHtml($this, $row));
    }

    /**
     * render a single row in the table
     * @param Column[] $columns
     * @param array    $row one row of the data
     * @return TableElement
     */
    protected function dataRow(array $columns, array $row): TableElement
    {
        $output = '';
        foreach ($columns as $column) {
            $output .= $this->dataCell($column, $row);
        }
        $tr = new TableElement('tr');
        $tr->setContent($output);
        foreach ($this->rowDecorators as $decorator) {
            $decorator->innerRow($tr, $columns, $row);
        }
        return $tr;
    }

    /**
     * render all the rows in the table
     * @param Column[] $columns
     * @param iterable $data
     * @return string
     */
    protected function tableBody(array $columns, iterable $data): string
    {
        $output = '';
        foreach ($data as $row) {
            $output .= $this->dataRow($columns, $row);
        }
        return $output;
    }

    /**
     * @param Column[] $columns
     * @return string
     */
    protected function afterLastRow(array $columns): string
    {
        $output = '';
        foreach ($this->rowDecorators as $decorator) {
            $output .= $decorator->afterLastRow($columns);
        }
        return $output;
    }
}
