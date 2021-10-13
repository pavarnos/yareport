<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

use LSS\YAReport\Render\RenderInterface;

/**
 * Format as a number
 */
class IntegerColumn extends Column
{
    public function __construct(string $id, string $title, string $description = '')
    {
        parent::__construct($id, $title, $description);
        $this->setRightAlign();
    }

    public function renderCell(RenderInterface $render, array $row): void
    {
        $value = $row[$this->getID()] ?? null;
        if (is_null($value)) {
            $render->appendBlank();
        } else {
            $render->appendInteger(intval($value));
        }
    }
}
