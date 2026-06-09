<?php

namespace App\Filament\Resources\Curhats\Pages;

use App\Filament\Resources\Curhats\CurhatResource;
use App\Models\CurhatMessage;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCurhat extends CreateRecord
{
    protected static string $resource = CurhatResource::class;

    protected ?string $initialMessage = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->initialMessage = trim((string) ($data['initial_message'] ?? ''));

        unset($data['initial_message']);

        $data['student_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'aktif';
        $data['last_message_at'] = now();

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->initialMessage === null || $this->initialMessage === '') {
            return;
        }

        CurhatMessage::create([
            'curhat_id' => $this->record->id,
            'user_id' => Auth::id(),
            'sender_type' => Auth::user()?->type,
            'is_read' => false,
            'message' => [
                'body' => $this->initialMessage,
            ],
        ]);
    }
}



