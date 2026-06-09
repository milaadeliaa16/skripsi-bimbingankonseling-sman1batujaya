<?php

namespace App\Filament\Resources\Curhats\Pages;

use App\Filament\Resources\Curhats\CurhatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCurhats extends ListRecords
{
    protected static string $resource = CurhatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
