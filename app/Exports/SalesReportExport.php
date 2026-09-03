<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles, WithColumnFormatting
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function collection(): \Illuminate\Support\Enumerable
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return [
            'No. Order',
            'Kasir',
            'Meja',
            'Tipe',
            'Subtotal',
            'Pajak',
            'Diskon',
            'Total',
            'Status',
            'Waktu',
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): ?array
    {
        return [
            '1' => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF398263'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => '"Rp" #,##0.00',
            'F' => '"Rp" #,##0.00',
            'G' => '"Rp" #,##0.00',
            'H' => '"Rp" #,##0.00',
        ];
    }
}