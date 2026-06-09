<?php

namespace App\Filament\Resources\KonselingGuruBk\Pages;

use App\Filament\Resources\KonselingGuruBk\KonselingGuruBkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKonselingGuruBk extends ListRecords
{
    protected static string $resource = KonselingGuruBkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
