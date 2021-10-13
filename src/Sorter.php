<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport;

/**
 * Holds persistent status for the users currently selected column and whether it is ascending or descending sort order.
 * If you call setColumn('id') it will sort ascending. Call it again to toggle to descending, again to ascending
 */
class Sorter
{
    public const PARAMETER_NAME = 'sort';

    /** @var bool ascending | descending */
    private bool $isAscending = true;

    public function __construct(private ?string $column = null)
    {
    }

    public static function fromArray(array $values): self
    {
        $result              = new self($values['column'] ?? null);
        $result->isAscending = !empty($values['ascending']);
        return $result;
    }

    public function toArray(): array
    {
        return ['column' => $this->column, 'ascending' => $this->isAscending];
    }

    /**
     * set the column to sort by
     * @param ?string $id key of the column to sort by
     */
    public function setColumn(?string $id): void
    {
        if ($this->column === $id) {
            // same column is selected: toggle sort order
            $this->isAscending = !$this->isAscending;
        } else {
            // a new column is selected
            $this->column      = $id;
            $this->isAscending = true;
        }
    }

    public function getColumn(): ?string
    {
        return $this->column;
    }

    public function getIsAscending(): bool
    {
        return $this->isAscending;
    }
}
