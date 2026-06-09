<?php

namespace App\Filament\Widgets;

use App\Models\Absence;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsenceTopLateStudentsChart extends ChartWidget
{
    protected ?string $heading = 'Siswa dengan Terlambat Terbanyak (Bulan Ini)';

    protected ?string $description = 'Diurutkan berdasarkan total catatan terlambat tertinggi.';

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
            ->join('users', 'users.id', '=', 'absences.student_id')
            ->where('absences.status', 'terlambat')
            ->whereBetween('absences.date', [$startOfMonth, $endOfMonth])
            ->selectRaw('users.name as label, COUNT(*) as total')
            ->groupBy('absences.student_id', 'users.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Terlambat',
                    'data' => $rows->pluck('total')->all(),
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
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
