<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   11 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport;

use LSS\YAReport\Column\Column;

/**
 * Allows you to save a report spec in a json file or database column, and restore it later.
 * If you have optional columns, you can create a UI that allows users to add and re-order the columns they
 * can see, change title etc. Then save this to the database and restore it later so their reports persist across
 * sessions
 */
class ReportSerializer
{
    public function fromArray(Report $template, array $columnInfo): Report
    {
        $result        = new Report();
        $seenColumnIDs = [];
        foreach ($columnInfo as $info) {
            if ($template->hasColumn($info['id'])) {
                $column = clone($template->getColumn($info['id']));
                $column->setTitle($info['title']);
                $result->addColumn($column);
            }
            // else column no longer exists: little we can do except ignore the problem
        }
        // make sure all required columns are included first
        foreach (array_diff_key($this->getRequiredColumns($template), $result->getAllColumns()) as $column) {
            $result->addColumn(clone($column));
        }
        // then make sure all the rest of the columns are added back in, but hidden
        foreach (array_diff_key($template->getAllColumns(), $result->getAllColumns()) as $column) {
            $result->addOptionalColumn(clone($column));
        }
        return $result;
    }

    public function toArray(Report $report): array
    {
        $result = [];
        foreach ($report->getVisibleColumns() as $column) {
            $result[] = ['id' => $column->getID(), 'title' => $column->getTitle()];
        }
        return $result;
    }

    public function fromJson(Report $template, string $json): Report
    {
        return $this->fromArray($template, (array) \Safe\json_decode($json ?: '[]', true));
    }

    public function toJson(Report $report): string
    {
        return \Safe\json_encode($this->toArray($report));
    }

    private function getRequiredColumns(Report $template): array
    {
        return array_filter($template->getAllColumns(), fn(Column $column) => $column->isRequired());
    }
}
