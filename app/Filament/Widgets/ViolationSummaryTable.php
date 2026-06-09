<?php

namespace App\Filament\Widgets;

use App\Exports\ViolationSummaryExport;
use App\Models\Kelas;
use App\Models\KonselingGuruBk;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViolationSummaryTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Ringkasan Pelanggaran Siswa';

    public static function canView(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return in_array($user->type, [User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH], true)
            || $user->hasAnyRole([User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getViolationSummaryQuery())
            ->defaultSort('total_cases', 'desc')
            ->defaultKeySort(false)
            ->headerActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('danger')
                    ->action(fn(): StreamedResponse | null => $this->exportPdf()),
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(fn(): BinaryFileResponse | null => $this->exportExcel()),
            ])
            ->columns([
                TextColumn::make('student_name')
                    ->label('Nama Siswa')
                    ->searchable(
                        query: fn(Builder $query, string $search): Builder => $query->where('users.name', 'like', "%{$search}%")
                    )
                    ->sortable(),
                TextColumn::make('kelas_name')
                    ->label('Kelas')
                    ->searchable(
                        query: fn(Builder $query, string $search): Builder => $query->where('kelas.name', 'like', "%{$search}%")
                    )
                    ->sortable(),
                TextColumn::make('type_of_violation')
                    ->label('Jenis Pelanggaran')
                    ->searchable(
                        query: fn(Builder $query, string $search): Builder => $query->where('konseling_guru_bks.type_of_violation', 'like', "%{$search}%")
                    )
                    ->sortable(),
                TextColumn::make('total_cases')
                    ->label('Total Kasus')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_points')
                    ->label('Total Poin')
                    ->badge()
                    ->color('danger')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('last_counseling_at')
                    ->label('Konseling Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->options(fn() => Kelas::query()->orderBy('name')->pluck('name', 'id'))
                    ->query(
                        fn(Builder $query, array $data): Builder => $query->when(
                            filled($data['value'] ?? null),
                            fn(Builder $query): Builder => $query->where('users.kelas_id', $data['value']),
                        )
                    )
                    ->searchable(),
            ])
            ->paginated([10, 25, 50])
            ->recordActions([])
            ->toolbarActions([])
            ->searchPlaceholder('Cari siswa / pelanggaran');
    }

    public function exportPdf(): StreamedResponse | null
    {
        $records = $this->getFilteredReportRecords();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data pelanggaran untuk diexport sesuai filter saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $generatedAt = now();

        $pdf = Pdf::loadView('pdf.violation-summary-report', [
            'records' => $records,
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'report-pelanggaran-' . $generatedAt->format('Ymd-His') . '.pdf'
        );
    }

    public function exportExcel(): BinaryFileResponse | null
    {
        $records = $this->getFilteredReportRecords();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data pelanggaran untuk diexport sesuai filter saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $generatedAt = now();

        return Excel::download(
            new ViolationSummaryExport($records),
            'report-pelanggaran-' . $generatedAt->format('Ymd-His') . '.xlsx'
        );
    }

    protected function getViolationSummaryQuery(): Builder
    {
        return KonselingGuruBk::query()
            ->join('users', 'users.id', '=', 'konseling_guru_bks.student_id')
            ->leftJoin('kelas', 'kelas.id', '=', 'users.kelas_id')
            ->whereNotNull('konseling_guru_bks.type_of_violation')
            ->where('konseling_guru_bks.type_of_violation', '!=', '')
            ->selectRaw('
                MIN(konseling_guru_bks.id) as id,
                users.name as student_name,
                kelas.name as kelas_name,
                konseling_guru_bks.type_of_violation,
                COUNT(*) as total_cases,
                COALESCE(SUM(konseling_guru_bks.point_of_violation), 0) as total_points,
                MAX(konseling_guru_bks.scheduled_at) as last_counseling_at
            ')
            ->groupBy('users.name', 'kelas.name', 'konseling_guru_bks.type_of_violation');
    }

    private function getFilteredReportRecords(): Collection
    {
        return $this->getFilteredSortedTableQuery()->get();
    }
}
