<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   02 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Column\EmailColumn;
use LSS\YAReport\Report;

/**
 * Extract email addresses as an array
 * Have used this as a format option via an ajax request on the bottom of the html report table:
 * click the Email Addresses link to extract all the valid emails from the whole data set and display for copy-paste
 * into an email client
 *
 * makeName() should take a row and return the human friendly name for the email address or ''
 */
class EmailRender
{
    /**
     * @param Report        $report
     * @param iterable      $data
     * @param callable|null $makeName callable(array):string
     * @return array
     */
    public function render(Report $report, iterable $data, callable $makeName = null): array
    {
        $emailColumns = $this->findEmailColumns($report);
        $makeName     ??= fn(array $row) => '';
        $output       = [];
        foreach ($data as $row) {
            foreach ($emailColumns as $emailColumn) {
                if (empty($row[$emailColumn])) {
                    continue;
                }
                $output[] = $this->format($row[$emailColumn], $makeName($row));
            }
        }
        return array_unique($output);
    }

    private function format(string $email, string $name): string
    {
        return empty($name) ? $email : ($name . ' <' . $email . '>');
    }

    protected function findEmailColumns(Report $report): array
    {
        $result = [];
        foreach ($report->getAllColumns() as $column) {
            if ($column instanceof EmailColumn) {
                $result[] = $column->getID();
            }
        }
        return $result;
    }
}
