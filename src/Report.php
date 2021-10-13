<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport;

use LSS\YAReport\Column\Column;

/**
 * Contains all the configuration needed for a report.
 * The report is passed to a renderer with some data for display.
 *
 * The report has a set of columns, some of which are visible and some are not.
 * Required columns must always be visible
 * Optional (non visible) columns are available to be added by the user at some later time
 */
class Report
{
    /** @var Column[] columns currently showing in the report */
    private array $visibleColumns = [];

    /** @var Column[] all columns that could possibly be in the report */
    private array $allColumns = [];

    /**
     * add a new column to the report: displayed by default, but the user may choose to hide them
     * @param Column $column
     * @return Column
     */
    public function addColumn(Column $column): Column
    {
        $this->visibleColumns[$column->getID()] = $column;
        $this->allColumns[$column->getID()]     = $column;
        return $column;
    }

    /**
     * not displayed by default
     * @param Column $column
     * @return Column
     */
    public function addOptionalColumn(Column $column): Column
    {
        assert(!$column->isRequired(), 'a required column must always be visible');
        $this->allColumns[$column->getID()] = $column;
        return $column;
    }

    /**
     * get the columns currently showing in the report
     * @return Column[]
     */
    public function getVisibleColumns(): array
    {
        return $this->visibleColumns;
    }

    public function isVisibleColumn(string $columnId): bool
    {
        return isset($this->visibleColumns[$columnId]);
    }

    public function hasColumn(string $columnId): bool
    {
        return isset($this->allColumns[$columnId]);
    }

    /**
     * @param string $columnId the column id
     * @return Column
     * @throws ReportException
     */
    public function getColumn(string $columnId): Column
    {
        if (!isset($this->allColumns[$columnId])) {
            throw new ReportException('Column is not in the report ' . $columnId);
        }
        return $this->allColumns[$columnId];
    }

    /**
     * @return Column[] indexed by id
     */
    public function getAllColumns(): array
    {
        return $this->allColumns;
    }

    public function hasOptionalColumns(): bool
    {
        // true if there are some columns that are not visible
        return !empty(array_diff_key($this->allColumns, $this->visibleColumns));
    }

    /**
     * returns an array of column names in a query to pass to Select::order()
     * @param Sorter $sorter
     * @return array
     */
    public function getSortOrder(Sorter $sorter): array
    {
        if (is_null($sorter->getColumn()) || !$this->isVisibleColumn($sorter->getColumn())) {
            return $this->getDefaultSortOrder();
        }
        $column = $this->getColumn($sorter->getColumn());
        return $sorter->getIsAscending() ? $column->getAscending() : $column->getDescending();
    }

    public function getDefaultSortOrder(): array
    {
        $columns = $this->getVisibleColumns();
        if (empty($columns)) {
            return [];
        }
        $first = current($columns);
        return $first->getAscending();
    }
}
