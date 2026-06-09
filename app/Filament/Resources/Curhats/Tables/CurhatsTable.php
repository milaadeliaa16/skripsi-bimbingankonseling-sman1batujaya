<?php

namespace App\Filament\Resources\Curhats\Tables;

use App\Models\Curhat;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CurhatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('15s')
            ->defaultSort('last_message_at', 'desc')
            ->description('Percakapan siswa dan guru BK')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('counterpart')
                    ->label('Lawan Bicara')
                    ->state(function (Curhat $record): ?string {
                        if (Auth::id() === $record->student_id) {
                            return $record->teacher?->name;
                        }

                        return $record->is_anonymous
                            ? 'Siswa (Anonim)'
                            : $record->student?->name;
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->whereHas('student', fn ($studentQuery) => $studentQuery->where('name', 'like', "%{$search}%"))
                            ->orWhereHas('teacher', fn ($teacherQuery) => $teacherQuery->where('name', 'like', "%{$search}%"));
                    }),

                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'aktif',
                        'warning' => 'menunggu',
                        'success' => 'selesai',
                    ]),

                TextColumn::make('unread_messages_count')
                    ->label('Inbox')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'warning' : 'gray'),

                TextColumn::make('last_message_at')
                    ->label('Pesan Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'menunggu' => 'Menunggu',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}



