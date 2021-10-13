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

class BooleanColumn extends Column
{
    protected string $yesHtml = 'Yes';

    protected string $noHtml = '';

    public function setYesHtml(string $yesHtml): static
    {
        $this->yesHtml = $yesHtml;
        return $this;
    }

    public function setNoHtml(string $noHtml): static
    {
        $this->noHtml = $noHtml;
        return $this;
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
        $value = $row[$this->getID()] ?? null;
        if (is_null($value)) {
            $render->appendBlank();
        } else {
            $render->appendBoolean(!empty($value));
        }
    }

    public function renderCellHtml(HtmlRenderInterface $render, array $row): string
    {
        return empty($row[$this->getID()]) ? $this->noHtml : $this->yesHtml;
    }
}
