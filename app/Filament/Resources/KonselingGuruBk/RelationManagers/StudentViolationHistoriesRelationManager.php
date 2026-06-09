<?php

namespace App\Filament\Resources\KonselingGuruBk\RelationManagers;

use App\Models\KonselingGuruBk;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class StudentViolationHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'studentViolationHistories';

    protected static ?string $title = 'Riwayat Pelanggaran Siswa';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query): void {
                $query
                    ->whereKeyNot($this->getOwnerRecord()->getKey())
                    ->orderByDesc('scheduled_at')
                    ->orderByDesc('created_at');
            })
            ->columns([
                TextColumn::make('type_of_violation')
                    ->label('Jenis Pelanggaran')
                    ->default('-')
                    ->searchable(),
                TextColumn::make('point_of_violation')
                    ->label('Poin')
                    ->badge()
                    ->color('danger')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('scheduled_at')
                    ->label('Tanggal Pelanggaran')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(30),
            ])
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->emptyStateHeading('Belum ada riwayat pelanggaran')
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    protected static function extractNotesText(mixed $notes): string
    {
        if (is_string($notes)) {
            return filled(trim($notes)) ? Str::of(strip_tags($notes))->squish()->toString() : '-';
        }

        if (is_array($notes)) {
            $tipTapTexts = collect(data_get($notes, 'content', []))
                ->flatMap(fn(array $node): array => collect(data_get($node, 'content', []))
                    ->pluck('text')
                    ->filter(fn(mixed $text): bool => is_string($text) && filled(trim($text)))
                    ->values()
                    ->all())
                ->filter()
                ->values();

            if ($tipTapTexts->isNotEmpty()) {
                return Str::of($tipTapTexts->join(' '))->squish()->toString();
            }

            $candidate = data_get($notes, 'content');

            if (is_string($candidate) && filled(trim($candidate))) {
                return Str::of(strip_tags($candidate))->squish()->toString();
            }
        }

        return '-';
    }
}
