<?php

namespace App\Filament\Resources\KonselingGuruBk\Pages;

use App\Filament\Resources\KonselingGuruBk\KonselingGuruBkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKonselingGuruBk extends CreateRecord
{
    protected static string $resource = KonselingGuruBkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['counselor_id'] = auth()->id();
        $data['is_read_by_student'] = false;
        $data['read_at_by_student'] = null;

        return $data;
    }
}
