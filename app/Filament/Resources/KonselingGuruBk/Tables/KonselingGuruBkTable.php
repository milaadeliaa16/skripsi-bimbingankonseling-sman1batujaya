<?php

namespace App\Filament\Resources\KonselingGuruBk\Tables;

use App\Models\KonselingGuruBk;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KonselingGuruBkTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('counselor.name')
                    ->label('Guru BK')
                    ->toggleable(),
                TextColumn::make('type_of_violation')
                    ->label('Jenis Pelanggaran')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('is_read_by_student')
                    ->label('Sudah di Buka Siswa ?')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Sudah Dibaca' : 'Belum Dibaca')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ])
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('point_of_violation')
                    ->label('Poin Pelanggaran')
                    ->limit(40)
                    ->badge()
                    ->color('danger')
                    ->searchable()
                    ->toggleable()
                    ->alignment(Alignment::Center),
                TextColumn::make('scheduled_at')
                    ->label('Tanggal Konseling')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->sortable()
                    ->dateTime()
                    ->toggleable(),
            ])
            ->heading('Konseling Guru BK (' . KonselingGuruBk::query()->where('counselor_id', Auth::id())->count() . ' total)')
            ->description('Konseling dari Guru BK ke Siswa')
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn(KonselingGuruBk $record): ?string => $record->is_read_by_student ? null : 'font-semibold')
            ->filters([
                SelectFilter::make('is_read_by_student')
                    ->label('Status Baca Siswa')
                    ->options([
                        1 => 'Sudah Dibaca',
                        0 => 'Belum Dibaca',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()
                    ->visible(fn(): bool => Auth::user()?->type === User::ROLE_GURU_BK),
                EditAction::make()
                    ->visible(fn(): bool => Auth::user()?->type === User::ROLE_GURU_BK),
                DeleteAction::make()
                    ->visible(fn(): bool => Auth::user()?->type === User::ROLE_GURU_BK),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn(): bool => Auth::user()?->type === User::ROLE_GURU_BK),
                ]),
            ]);
    }
}
