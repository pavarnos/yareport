<?php
/**
 * @file
 * @author Lightly Salted Software Ltd
 * @date   02 Oct 2021
 */

declare(strict_types=1);

namespace LSS\YAReport\Render;

use LSS\YAReport\Report;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * render to PhpSpreadsheet
 */
class ExcelRender implements RenderInterface
{
    private Spreadsheet $document;

    private Worksheet $sheet;

    private int $rowIndex = 1;

    private int $columnIndex = 1;

    public function render(Report $report, iterable $data): Spreadsheet
    {
        $this->document = $this->getDocument();
        $this->sheet = $this->setupSheet($this->document->getActiveSheet());
        $this->rowIndex = 1;
        $this->columnIndex = 1;

        $columns = $report->getVisibleColumns();
        $this->renderHeader($columns);

        foreach ($data as $row) {
            $this->rowIndex++;
            $this->columnIndex = 1;
            foreach ($columns as $column) {
                $column->renderCell($this, $row);
            }
        }

        $this->setColumnSizes($columns);
        return $this->document;
    }

    public function appendString(string $value): void
    {
        $this->sheet->setCellValueByColumnAndRow($this->columnIndex++, $this->rowIndex, $value);
    }

    public function appendInteger(int $value): void
    {
        $this->sheet->setCellValueByColumnAndRow($this->columnIndex++, $this->rowIndex, $value);
    }

    public function appendFloat(float $value): void
    {
        $this->sheet->setCellValueByColumnAndRow($this->columnIndex++, $this->rowIndex, $value);
    }

    public function appendBoolean(bool $value): void
    {
        $this->sheet->setCellValueByColumnAndRow($this->columnIndex++, $this->rowIndex, $value);
    }

    public function appendBlank(): void
    {
        $this->columnIndex++;
    }

    public function getDocument(): Spreadsheet
    {
        if (empty($this->document)) {
            $this->document = new Spreadsheet();
//          $document->getProperties()->setCreator();
            $this->document->setActiveSheetIndex(0);
//          if (!empty($title)) {
//              $document->getProperties()->setTitle($title)->setSubject($title);
//          }
        } // else add new sheet and select it
        return $this->document;
    }

    protected function setupSheet(Worksheet $sheet): Worksheet
    {
        $sheet->getPageSetup()
              ->setFitToPage(true)
              ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageMargins()
              ->setTop(0.5)
              ->setBottom(0.5)
              ->setLeft(0.5)
              ->setRight(0.5);
        return $sheet;
    }

    protected function renderHeader(array $columns): void
    {
        $this->columnIndex = 1;
        foreach ($columns as $column) {
            $column->renderTitle($this);
        }

        // bold the header
        $this->sheet->getStyle(
            Coordinate::stringFromColumnIndex(1) . $this->rowIndex . ':' .
            Coordinate::stringFromColumnIndex(count($columns)) . $this->rowIndex
        )->applyFromArray(
            [
                'font' => ['bold' => true,],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFEEEEEE']],
            ]
        );
    }

    protected function setColumnSizes(array $columns): void
    {
        $columnIndex = 1;
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach ($columns as $column) {
            $this->sheet->getColumnDimension(Coordinate::stringFromColumnIndex($columnIndex++))
                        ->setAutoSize(true);
        }
    }
}
