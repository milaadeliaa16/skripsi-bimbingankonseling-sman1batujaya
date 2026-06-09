<?php

namespace App\Filament\Resources\GuruBks\Pages;

use App\Filament\Resources\GuruBks\GuruBkResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Spatie\Permission\Models\Role;

class CreateGuruBk extends CreateRecord
{
    protected static string $resource = GuruBkResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = User::ROLE_GURU_BK;

        return $data;
    }

    protected function afterCreate(): void
    {
        $role = Role::query()->firstOrCreate([
            'name' => User::ROLE_GURU_BK,
            'guard_name' => 'web',  
        ]);

        $this->getRecord()->syncRoles([$role->name]);
    }
}
