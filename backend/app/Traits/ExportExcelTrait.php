<?php

namespace App\Traits;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

trait ExportExcelTrait
{
    private $cellRange;
    private $total;
    private $models = [];
    private $number;
    private $title;

    public function setUp($models, $number, $title, $cellRange)
    {
        $this->models = $models;
        $this->number = $number;
        $this->total = $models->count() + 1;
        $this->title = $title;
        $this->cellRange = $cellRange;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function array(): array
    {
        return $this->models;
    }

    public function collection()
    {
        return $this->models;
    }

    public function styleArray(): array
    {
        return [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = "A1:{$this->cellRange}1"; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()
                             ->getStyle("A1:{$this->cellRange}{$this->total}")
                             ->applyFromArray($this->styleArray());
            },
        ];
    }
}
