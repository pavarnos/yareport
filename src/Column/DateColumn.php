<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use Carbon\Carbon;
use LSS\YAReport\Render\HtmlRenderInterface;

/**
 * Show a formatted date
 */
class DateColumn extends Column
{
    /** @var string use a format string understood by Carbon */
    protected string $format = 'Y-m-d';

    protected string $timeZone = 'utc';

    public function setFormat(string $format): static
    {
        $this->format = $format;
        return $this;
    }

    public function setTimeZone(string $timeZone): static
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        $utcDate = $row[$this->getID()] ?? '';
        if (empty($utcDate)) {
            return '';
        } else {
            return (new Carbon($utcDate))->setTimezone($this->timeZone)->format($this->format);
        }
    }
}
