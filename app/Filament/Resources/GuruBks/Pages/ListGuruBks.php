<?php

namespace App\Filament\Resources\GuruBks\Pages;

use App\Filament\Resources\GuruBks\GuruBkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListGuruBks extends ListRecords
{
    protected static string $resource = GuruBkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New Guru BK')
                ->icon(Heroicon::Plus),
        ];
    }
}
