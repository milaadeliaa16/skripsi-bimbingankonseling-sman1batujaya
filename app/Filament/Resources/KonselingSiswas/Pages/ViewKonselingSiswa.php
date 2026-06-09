<?php

namespace App\Filament\Resources\KonselingSiswas\Pages;

use App\Filament\Resources\KonselingSiswas\KonselingSiswaResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewKonselingSiswa extends ViewRecord
{
    protected static string $resource = KonselingSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $user = Auth::user();

        if (! $user || $user->type !== User::ROLE_GURU_BK) {
            return;
        }

        if ((int) $this->getRecord()->counselor_id !== (int) $user->id) {
            return;
        }

        if ($this->getRecord()->is_read_by_counselor) {
            return;
        }

        $this->getRecord()->update([
            'is_read_by_counselor' => true,
            'read_at_by_counselor' => now(),
        ]);
    }
}
