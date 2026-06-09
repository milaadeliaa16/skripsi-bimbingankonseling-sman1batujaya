<?php

namespace App\Filament\Widgets;

use App\Models\KonselingGuruBk;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ViolationByClassChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pelanggaran per Kelas (Bulan Ini)';

    protected ?string $description = 'Total pelanggaran per kelas dalam bulan berjalan.';

    protected ?string $maxHeight = '320px';

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

        $rows = KonselingGuruBk::query()
            ->join('users', 'users.id', '=', 'konseling_guru_bks.student_id')
            ->leftJoin('kelas', 'kelas.id', '=', 'users.kelas_id')
            ->whereNotNull('konseling_guru_bks.type_of_violation')
            ->where('konseling_guru_bks.type_of_violation', '!=', '')
            ->whereBetween('konseling_guru_bks.created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('COALESCE(kelas.name, "Tanpa Kelas") as label, COUNT(*) as total')
            ->groupBy('users.kelas_id', 'kelas.name')
            ->orderByDesc('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pelanggaran',
                    'data' => $rows->pluck('total')->all(),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                ],
            ],
            'labels' => $rows->pluck('label')->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
