<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Absences\AbsenceResource;
use App\Filament\Resources\Siswas\SiswaResource;
use App\Models\Absence;
use App\Models\User;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AbsenceDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = SiswaResource::class;

    protected static ?string $title = 'Detail Absen Siswa';

    protected string $view = 'filament.resources.siswas.pages.absence-detail';

    public ?int $studentId = null;

    public function mount(int|string $record): void
    {
        $student = User::with('absences')
            ->where('type', User::ROLE_SISWA)
            ->findOrFail($record);

        $this->studentId = (int) $student->id;
        $this->heading = $student->name;
        $this->subheading = 'Absen Detail (' . $student->absences->count() . ' total)';
    }

    protected function getTableQuery(): Builder
    {
        abort_unless($this->studentId, 404);

        return Absence::query()
            ->where('student_id', $this->studentId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->sortable()
                    ->dateTime('d M Y H:i:s')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->colors([
                        'success' => 'hadir',
                        'danger' => 'alpa',
                        'secondary' => 'izin',
                        'primary' => 'sakit',
                        'warning' => 'terlambat',
                    ])
                    ->badge()
                    ->toggleable(),

                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'hadir' => 'Hadir',
                        'alpa' => 'Alpa',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'terlambat' => 'Terlambat',
                    ]),
                DateRangeFilter::make('date')
                    ->label('Filter Tanggal'),
            ])
            ->recordActions([
                ActionsAction::make('edit_absence')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn(Absence $record): string => AbsenceResource::getUrl('edit', ['record' => $record]), shouldOpenInNewTab: true),
                ActionsAction::make('delete_absence')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(Absence $record) => $record->delete()),
            ])
            ->toolbarActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make(),
                ]),
            ]);
    }
}
