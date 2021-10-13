<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   5 11 2019
 */

declare(strict_types=1);

namespace LSS\YAReport\Column;

class DateTimeColumn extends DateColumn
{
    protected string $format = 'Y-m-d H:i:s';
}
