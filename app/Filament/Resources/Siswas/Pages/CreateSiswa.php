<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = User::ROLE_SISWA;

        return $data;
    }

    protected function afterCreate(): void
    {
        $role = Role::query()->firstOrCreate([
            'name' => User::ROLE_SISWA,
            'guard_name' => 'web',
        ]);

        $this->getRecord()->syncRoles([$role->name]);
    }
}
