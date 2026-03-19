<?php

namespace App\Exports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;

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

    public function title(): string
    {
        return 'Doctors';
    }

    public function headings(): array
    {
        return [
            'SR No.',
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
            'Created At (IST)',
        ];
    }

    public function map($doctor): array
    {
        static $index = 0;
        $index++;

        $createdIST = Carbon::parse($doctor->created_at)
            ->setTimezone('Asia/Kolkata')
            ->format('d M Y, h:i A');

        return [
            $index,
            $doctor->name,
            $doctor->hospital_name,
            $doctor->city                    ?? '—',
            $doctor->employee->name          ?? '—',
            $doctor->employee->employee_code ?? '—',
            $doctor->employee->city          ?? '—',
            $doctor->employee->address       ?? '—',
            $doctor->employee->phone         ?? '—',
            $doctor->employee->email         ?? '—',
            $doctor->photo                   ?? '—',
            $createdIST,
        ];
    }

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
            'L' => 26,   // Created At (IST)
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'L';

        // Freeze header row
        $sheet->freezePane('A2');

        // Row heights
        $sheet->getRowDimension(1)->setRowHeight(26);

        // Wrap text for long columns
        $sheet->getStyle('H2:H' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('J2:J' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('K2:K' . $lastRow)->getAlignment()->setWrapText(true);

        // Zebra striping — alternating teal tint rows
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E0F7F5');
            }
        }

        return [
            1 => [
                'font' => [
                    'bold'  => true,
                    'size'  => 10,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '009EA3'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                        'color'       => ['rgb' => 'B3569F'],
                    ],
                ],
            ],

            "A2:{$lastCol}{$lastRow}" => [
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCE6E5'],
                    ],
                ],
                'font' => [
                    'size'  => 9,
                    'color' => ['rgb' => '1E293B'],
                ],
            ],


            "A2:A{$lastRow}" => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font'      => [
                    'bold'  => true,
                    'color' => ['rgb' => '007A7F'],
                ],
            ],

            "B2:B{$lastRow}" => [
                'font' => [
                    'bold'  => true,
                    'size'  => 9,
                    'color' => ['rgb' => '1E293B'],
                ],
            ],

            "F2:F{$lastRow}" => [
                'font' => [
                    'bold'  => true,
                    'color' => ['rgb' => '8B3A7A'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],


            "L2:L{$lastRow}" => [
                'font' => [
                    'size'  => 8,
                    'color' => ['rgb' => '334155'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
