<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKonselingSiswa extends EditRecord
{
    protected static string $resource = KonselingSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()?->type === \App\Models\User::ROLE_SISWA) {
            $data['is_read_by_counselor'] = false;
            $data['read_at_by_counselor'] = null;
        }

        return $data;
    }
}
