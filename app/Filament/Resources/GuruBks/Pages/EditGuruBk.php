<?php

namespace App\Filament\Resources\GuruBks\Pages;

use App\Filament\Resources\GuruBks\GuruBkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGuruBk extends EditRecord
{
    protected static string $resource = GuruBkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
