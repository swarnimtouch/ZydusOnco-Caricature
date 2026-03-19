<?php

namespace App\Exports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

class DoctorExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    /**
     * Data collection with optional search filter
     */
    public function collection()
    {
        return Doctor::with('employee')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhereHas('employee', function ($q) {
                        $q->where('employee_code', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->latest()
            ->get();
    }

    /**
     * Sheet title
     */
    public function title(): string
    {
        return 'Doctors';
    }

    /**
     * Column headings — Doctor fields pehle, phir Employee
     */
    public function headings(): array
    {
        return [
            '#',
            'Doctor Name',
            'Hospital Name',
            'Doctor City',
            'Employee Name',
            'Employee Code',
            'Employee City',
            'Employee Address',
            'Employee Mobile',
            'Employee Email',
            'Photo URL',
            'Created At',
        ];
    }

    /**
     * Map each row — same order as headings
     */
    public function map($doctor): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $doctor->name,
            $doctor->hospital_name,
            $doctor->city ?? '—',
            $doctor->employee->name        ?? '—',
            $doctor->employee->employee_code ?? '—',
            $doctor->employee->city        ?? '—',
            $doctor->employee->address     ?? '—',
            $doctor->employee->phone       ?? '—',
            $doctor->employee->email       ?? '—',
            $doctor->photo                 ?? '—',
            $doctor->created_at->format('d M Y, h:i A'),
        ];
    }

    /**
     * Column widths
     */
    public function columnWidths(): array
    {
        return [
            'A' => 6,    // #
            'B' => 24,   // Doctor Name
            'C' => 26,   // Hospital Name
            'D' => 18,   // Doctor City
            'E' => 24,   // Employee Name
            'F' => 18,   // Employee Code
            'G' => 18,   // Employee City
            'H' => 32,   // Employee Address
            'I' => 18,   // Employee Mobile
            'J' => 30,   // Employee Email
            'K' => 40,   // Photo URL
            'L' => 22,   // Created At
        ];
    }

    /**
     * Sheet styling
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'L';

        $sheet->insertNewRowBefore(1, 1);
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'Doctor Records — Exported on ' . now()->format('d M Y, h:i A'));

        $sheet->freezePane('A3');

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $sheet->getStyle('H3:H' . ($lastRow + 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('J3:J' . ($lastRow + 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('K3:K' . ($lastRow + 1))->getAlignment()->setWrapText(true);

        for ($row = 3; $row <= $lastRow + 1; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F4F6FB');
            }
        }

        return [
            1 => [
                'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F1E36']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            2 => [
                'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A3A5C']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '0A7C74']]],
            ],

            "A3:{$lastCol}" . ($lastRow + 1) => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => false],
                'borders'   => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E0E4EC']],
                ],
                'font' => ['size' => 9],
            ],

            "A3:A" . ($lastRow + 1) => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font'      => ['bold' => true, 'color' => ['rgb' => '7B9FF5']],
            ],

            "B3:B" . ($lastRow + 1) => [
                'font' => ['bold' => true, 'size' => 9],
            ],

            "F3:F" . ($lastRow + 1) => [
                'font'      => ['bold' => true, 'color' => ['rgb' => '5BC0AA']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],

            "L3:L" . ($lastRow + 1) => [
                'font'      => ['color' => ['rgb' => '7A8A9A'], 'size' => 8],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
