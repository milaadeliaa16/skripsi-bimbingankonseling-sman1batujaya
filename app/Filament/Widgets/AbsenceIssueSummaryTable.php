<?php

namespace App\Filament\Widgets;

use App\Exports\AbsenceIssueSummaryExport;
use App\Models\Absence;
use App\Models\Kelas;
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

class AbsenceIssueSummaryTable extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Ringkasan Masalah Absensi Siswa';

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
            ->query($this->getAbsenceIssueSummaryQuery())
            ->defaultSort('total_issue_count', 'desc')
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
                    ->sortable(),
                TextColumn::make('total_alpa')
                    ->label('Alpa')
                    ->badge()
                    ->color('danger')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_izin')
                    ->label('Izin')
                    ->badge()
                    ->color('secondary')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_terlambat')
                    ->label('Terlambat')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_sakit')
                    ->label('Sakit')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('total_issue_count')
                    ->label('Total Masalah Absensi')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('last_issue_date')
                    ->label('Tanggal Terakhir')
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
            ->searchPlaceholder('Cari nama siswa');
    }

    public function exportPdf(): StreamedResponse | null
    {
        $records = $this->getFilteredReportRecords();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data absensi untuk diexport sesuai filter saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $generatedAt = now();

        $pdf = Pdf::loadView('pdf.absence-issue-summary-report', [
            'records' => $records,
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'report-masalah-absensi-' . $generatedAt->format('Ymd-His') . '.pdf'
        );
    }

    public function exportExcel(): BinaryFileResponse | null
    {
        $records = $this->getFilteredReportRecords();

        if ($records->isEmpty()) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->body('Tidak ada data absensi untuk diexport sesuai filter saat ini.')
                ->warning()
                ->send();

            return null;
        }

        $generatedAt = now();

        return Excel::download(
            new AbsenceIssueSummaryExport($records),
            'report-masalah-absensi-' . $generatedAt->format('Ymd-His') . '.xlsx'
        );
    }

    protected function getAbsenceIssueSummaryQuery(): Builder
    {
        return Absence::query()
            ->join('users', 'users.id', '=', 'absences.student_id')
            ->leftJoin('kelas', 'kelas.id', '=', 'users.kelas_id')
            ->whereIn('absences.status', ['alpa', 'izin', 'terlambat', 'sakit'])
            ->selectRaw('
                MIN(absences.id) as id,
                users.name as student_name,
                kelas.name as kelas_name,
                SUM(CASE WHEN absences.status = "alpa" THEN 1 ELSE 0 END) as total_alpa,
                SUM(CASE WHEN absences.status = "izin" THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN absences.status = "terlambat" THEN 1 ELSE 0 END) as total_terlambat,
                SUM(CASE WHEN absences.status = "sakit" THEN 1 ELSE 0 END) as total_sakit,
                COUNT(*) as total_issue_count,
                MAX(absences.date) as last_issue_date
            ')
            ->groupBy('users.name', 'kelas.name');
    }

    private function getFilteredReportRecords(): Collection
    {
        return $this->getFilteredSortedTableQuery()->get();
    }
}
