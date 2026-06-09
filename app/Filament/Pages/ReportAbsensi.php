<?php

namespace App\Filament\Pages;

use App\Exports\ReportAbsensiExport;
use App\Models\Absence;
use App\Models\User;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class ReportAbsensi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static string|UnitEnum|null $navigationGroup = 'Guru BK';

    protected static ?string $navigationLabel = 'Report Absensi';

    protected static ?string $title = 'Report Absensi';

    protected static ?int $navigationSort = 11;

    protected string $view = 'filament.pages.report-absensi';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return in_array($user->type, [User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH], true)
            || $user->hasAnyRole([User::ROLE_GURU_BK, User::ROLE_KEPALA_SEKOLAH]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    protected function getHeaderActions(): array
    {
        return [
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
        ];
    }

    protected function getTableQuery(): Builder
    {
        return Absence::query()
            ->with(['student', 'kelas'])
            ->whereIn('status', ['alpa', 'izin', 'terlambat', 'sakit']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'danger' => 'alpa',
                        'secondary' => 'izin',
                        'warning' => 'terlambat',
                        'primary' => 'sakit',
                    ]),

                TextColumn::make('date')
                    ->label('Tanggal Absensi (Bagi yang terlambat)')
                    ->state(fn(Absence $record): string => $record->status === 'terlambat'
                        ? $record->date?->format('d M Y H:i')
                        : '-')
                    ->sortable(query: fn(Builder $query, string $direction): Builder => $query->orderBy('date', $direction))
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'alpa' => 'Alpa',
                        'izin' => 'Izin',
                        'terlambat' => 'Terlambat',
                        'sakit' => 'Sakit',
                    ]),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'name'),
                DateRangeFilter::make('date')
                    ->label('Tanggal')
                    ->label('Filter by Date Range')
                    ->timezone('asia/jakarta')
                    ->alwaysShowCalendar(),
            ])
            ->recordActions([])
            ->toolbarActions([])
            ->description('Laporan absensi siswa dengan status absen (alpa, izin, terlambat, sakit) per hari/minggu/bulan. Silakan klik icon corong untuk memilih');
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

        $pdf = Pdf::loadView('pdf.report-absensi', [
            'records' => $records,
            'generatedAt' => $generatedAt,
        ])->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'report-absensi-' . $generatedAt->format('Ymd-His') . '.pdf'
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
            new ReportAbsensiExport($records),
            'report-absensi-' . $generatedAt->format('Ymd-His') . '.xlsx'
        );
    }

    private function getFilteredReportRecords(): Collection
    {
        return $this->getFilteredSortedTableQuery()
            ->with(['student', 'kelas'])
            ->get();
    }
}
