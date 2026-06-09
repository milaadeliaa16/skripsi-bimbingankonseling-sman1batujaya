<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Kelas;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->colors([
                        'success' => 'siswa',
                        'warning' => 'guru_bk',
                        'danger' => 'kepala_sekolah',
                    ])
                    ->badge()
                    ->sortable(),
                TextColumn::make('nip')
                    ->label('NIP')
                    ->toggleable(),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->toggleable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->heading('Users (' . User::count() . ' total)')
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Role')
                    ->options([
                        User::ROLE_GURU_BK => 'Guru BK',
                        User::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah',
                        User::ROLE_SISWA => 'Siswa',
                    ]),
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('name', 'id')),
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
