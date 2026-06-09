<?php

namespace App\Filament\Resources\KonselingSiswas\Tables;

use App\Models\KonselingSiswa;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KonselingSiswasTable
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('problem')
                    ->label('Masalah')
                    ->searchable()
                    ->toggleable()
                    ->limit(40),
                TextColumn::make('is_read_by_counselor')
                    ->label('Sudah di Buka Guru BK ?')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Sudah Dibaca' : 'Belum Dibaca')
                    ->colors([
                        'success' => true,
                        'warning' => false,
                    ])
                    ->toggleable()
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'dijadwalkan',
                        'danger' => 'ditindaklanjuti',
                        'success' => 'selesai',
                    ]),
                TextColumn::make('scheduled_at')
                    ->label('Jadwal Konseling')
                    ->dateTime('d M Y H:i')
                    ->searchable()
                    ->toggleable(),
            ])
            ->heading('Konseling Siswa (' . KonselingSiswa::query()->where('student_id', Auth::id())->count() . ' total)')
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn(KonselingSiswa $record): ?string => $record->is_read_by_counselor ? null : 'font-semibold')
            ->description('Konseling dari Siswa ke Guru BK')
            ->filters([
                SelectFilter::make('is_read_by_counselor')
                    ->label('Status Baca Guru BK')
                    ->options([
                        1 => 'Sudah Dibaca',
                        0 => 'Belum Dibaca',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dijadwalkan' => 'Dijadwalkan',
                        'selesai' => 'Selesai',
                        'ditindaklanjuti' => 'Ditindaklanjuti',
                    ])
                    ->label('Status'),
                SelectFilter::make('problem')
                    ->options([
                        'Bimbingan Pribadi' => 'Bimbingan Pribadi',
                        'Bimbingan Belajar' => 'Bimbingan Belajar',
                        'Bimbingan Sosial' => 'Bimbingan Sosial',
                        'Bimbingan Karir' => 'Bimbingan Karir',
                        'Bimbingan Konseling' => 'Bimbingan Konseling',
                    ])
                    ->label('Masalah'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn(): bool => Auth::user()?->type === User::ROLE_SISWA),
                DeleteAction::make()
                    ->visible(fn(): bool => Auth::user()?->type === User::ROLE_SISWA),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn(): bool => Auth::user()?->type === User::ROLE_SISWA),
                ]),
            ]);
    }
}
