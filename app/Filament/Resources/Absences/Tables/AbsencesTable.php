<?php

namespace App\Filament\Resources\Absences\Tables;

use App\Models\Absence;
use App\Models\Kelas;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AbsencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->sortable()
                    ->dateTime('d M Y H:i:s')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable(),
                TextColumn::make('kelas.name')
                    ->label('Kelas')
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
            ])
            ->heading('Absen (' . Absence::count() . ' total)')
            ->defaultSort('created_at', 'desc')
            ->description("Absen siswa per hari")
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('name', 'id')),
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
                    ->label('Tanggal'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
