<?php

/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\HtmlRenderInterface;
use LSS\YAReport\Render\RenderInterface;
use LSS\YAReport\ReportException;

/**
 * One column of a report
 *
 * This class is the base column for most others because almost everything gets rendered to a string eventually
 */
class Column
{
    private const SORT_AUTO = ['__sort_auto ']; // calculate descending sort order from the ascending one

    /** @var string[] database columns to sort by in ascending mode */
    private array $ascending = []; // empty = disabled

    /** @var string[] database columns to sort by in descending mode */
    private array $descending = []; // empty = disabled

    /** @var string[] style of column for html rendering */
    private array $cssStyles = [];

    /** @var bool true if this column should be right aligned */
    private bool $isRightAligned = false;

    /** @var bool true if this column must stay in the report (it cannot be removed when customising) */
    private bool $isRequired = false;

    /**
     * @param string $id          unique id: usually matches database column name
     * @param string $title       will be escaped for html
     * @param string $description alt title when hovering over link in html table. will be escaped in html
     */
    public function __construct(private string $id, private string $title, private string $description = '')
    {
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCSSStyles(): array
    {
        return $this->cssStyles;
    }

    public function addCSSStyle(string $style): static
    {
        if (!in_array($style, $this->cssStyles, true)) {
            $this->cssStyles[] = $style;
        }
        return $this;
    }

    public function isRightAligned(): bool
    {
        return $this->isRightAligned;
    }

    public function setRightAlign(bool $isRightAligned = true): static
    {
        $this->isRightAligned = $isRightAligned;
        return $this;
    }

    public function setIsRequired(bool $isRequired = true): static
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    /**
     * @param string[] $ascending  sql column names
     * @param string[] $descending sql column names
     * @return static
     */
    public function setSortOrder(array $ascending = [], array $descending = self::SORT_AUTO): static
    {
        $this->ascending  = $ascending;
        $this->descending = empty($ascending) ? [] : $descending;
        return $this;
    }

    /**
     * get ascending sort columns
     * @return string[]
     */
    public function getAscending(): array
    {
        return $this->ascending;
    }

    /**
     * get descending sort columns
     * @return string[]
     */
    public function getDescending(): array
    {
        if (empty($this->descending)) {
            throw new ReportException('Cannot sort descending by ' . $this->getID());
        }
        if ($this->descending !== self::SORT_AUTO) {
            return $this->descending;
        }
        // calculate the descending order from the ascending one
        $output = [];
        foreach ($this->getAscending() as $field) {
            $output[] = $field . ' DESC';
        }
        return $this->descending = $output;
    }

    public function renderTitle(RenderInterface $render): void
    {
        $render->appendString($this->getTitle());
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
        $value = $row[$this->getID()] ?? null;
        if (is_null($value)) {
            $render->appendBlank();
        } else {
            $render->appendString((string)($value));
        }
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        return $render->escape((string)($row[$this->getID()] ?? ''));
    }
}
