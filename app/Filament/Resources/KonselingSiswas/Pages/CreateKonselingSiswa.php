<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKonselingSiswa extends CreateRecord
{
    protected static string $resource = KonselingSiswaResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return KonselingSiswaResource::canCreate();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = auth()->id();
        $data['is_read_by_counselor'] = false;
        $data['read_at_by_counselor'] = null;

        return $data;
    }
}
