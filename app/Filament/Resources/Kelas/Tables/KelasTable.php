<?php

namespace App\Filament\Resources\Kelas\Tables;

use App\Models\Kelas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KelasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('grade')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('jurusan')
                    ->toggleable()
                    ->searchable()
                    ->alignCenter(),
                TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->heading('Kelas (' . Kelas::count() . ' total)')
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('name')
                    ->label('Kelas')
                    ->searchable()
                    ->options(
                        Kelas::query()
                            ->pluck('name', 'name')
                            ->toArray()
                    ),

                SelectFilter::make('grade')
                    ->label('Grade')
                    ->options([
                        10 => '10',
                        11 => '11',
                        12 => '12',
                    ]),

                SelectFilter::make('jurusan')
                    ->label('Jurusan')
                    ->options(
                        Kelas::query()
                            ->select('jurusan')
                            ->distinct()
                            ->pluck('jurusan', 'jurusan')
                            ->toArray()
                    ),
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
