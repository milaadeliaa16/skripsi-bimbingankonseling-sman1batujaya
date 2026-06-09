<?php

namespace App\Filament\Widgets;

use App\Models\Absence;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsenceTopLateClassesChart extends ChartWidget
{
    protected ?string $heading = 'Kelas dengan Siswa Terlambat Terbanyak (Bulan Ini)';

    protected ?string $description = 'Berdasarkan jumlah siswa unik berstatus terlambat.';

    protected ?string $maxHeight = '340px';

    protected int|string|array $columnSpan = 1;

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return in_array($user->type, [User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH], true)
            || $user->hasAnyRole([User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH]);
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $rows = Absence::query()
            ->leftJoin('kelas', 'kelas.id', '=', 'absences.kelas_id')
            ->where('absences.status', 'terlambat')
            ->whereBetween('absences.date', [$startOfMonth, $endOfMonth])
            ->selectRaw('COALESCE(kelas.name, "Tanpa Kelas") as label, COUNT(DISTINCT absences.student_id) as total')
            ->groupBy('absences.kelas_id', 'kelas.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa Terlambat',
                    'data' => $rows->pluck('total')->all(),
                    'backgroundColor' => '#06b6d4',
                    'borderColor' => '#0891b2',
                ],
            ],
            'labels' => $rows->pluck('label')->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
