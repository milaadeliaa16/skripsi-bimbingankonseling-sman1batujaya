<?php

namespace App\Filament\Resources\Siswas\Tables;

use App\Filament\Resources\Siswas\Pages\AbsenceDetail;
use App\Models\Absence;
use App\Models\Kelas;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('absences_count')
                    ->label('Jumlah Absen')
                    ->counts('absences')
                    ->alignCenter(),
            ])
            ->heading('Data Siswa (' . User::SISWA()->count() . ' Total)')
            ->defaultSort('created_at', 'desc')
            ->description('Absen per siswa')
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('name', 'id')),
            ])
            ->recordActions([
                Action::make('absence-detail')
                    ->label('Absen Detail')
                    ->url(fn(User $record) => AbsenceDetail::getUrl([
                        'record' => $record->id,
                    ]))
                    ->icon(Heroicon::OutlinedEye)
                    ->color('success'),
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
