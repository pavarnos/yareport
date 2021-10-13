<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   02 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

/**
 * accept data from a cell value for a column and render it to a finished report in json, csv, excel etc
 * @see HtmlRenderInterface for the html version which has very different needs
 */
interface RenderInterface
{
    public function appendString(string $value): void;

    public function appendInteger(int $value): void;

    public function appendFloat(float $value): void;

    public function appendBoolean(bool $value): void;

    public function appendBlank(): void; // empty cell
}
