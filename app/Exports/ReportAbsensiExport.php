<?php

namespace App\Exports;

use App\Models\Absence;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportAbsensiExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        private readonly Collection $records
    ) {}

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Status',
            'Tanggal Absensi',
        ];
    }

    /**
     * @param Absence $row
     */
    public function map($row): array
    {
        return [
            $row->student?->name ?? '-',
            $row->kelas?->name ?? '-',
            ucfirst((string) $row->status),
            $row->status === 'terlambat' && $row->date
                ? $row->date->format('d M Y H:i')
                : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1F4E78'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('D1:D' . $highestRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                for ($row = 2; $row <= $highestRow; $row++) {
                    $statusCell = 'C' . $row;
                    $status = strtolower((string) $sheet->getCell($statusCell)->getValue());

                    $statusColor = match ($status) {
                        'alpa' => 'FFEF4444',
                        'izin' => 'FF6B7280',
                        'terlambat' => 'FFF59E0B',
                        'sakit' => 'FF3B82F6',
                        default => null,
                    };

                    if (! $statusColor) {
                        continue;
                    }

                    $sheet->getStyle($statusCell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['argb' => 'FFFFFFFF'],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $statusColor],
                        ],
                    ]);
                }
            },
        ];
    }
}
