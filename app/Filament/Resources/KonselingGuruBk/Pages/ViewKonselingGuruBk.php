<?php

namespace App\Filament\Resources\KonselingGuruBk\Pages;

use App\Filament\Resources\KonselingGuruBk\KonselingGuruBkResource;
use App\Models\User;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewKonselingGuruBk extends ViewRecord
{
    protected static string $resource = KonselingGuruBkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach (['problem', 'summary', 'solution', 'notes'] as $field) {
            $data[$field] = $this->normalizeTipTapDocument($data[$field] ?? null);
        }

        return $data;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $user = Auth::user();

        if (! $user || $user->type !== User::ROLE_SISWA) {
            return;
        }

        if ((int) $this->getRecord()->student_id !== (int) $user->id) {
            return;
        }

        if ($this->getRecord()->is_read_by_student) {
            return;
        }

        $this->getRecord()->update([
            'is_read_by_student' => true,
            'read_at_by_student' => now(),
        ]);
    }

    protected function normalizeTipTapDocument(mixed $value): array
    {
        if (is_array($value) && ($value['type'] ?? null) === 'doc' && array_key_exists('content', $value)) {
            return $value;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded) && ($decoded['type'] ?? null) === 'doc' && array_key_exists('content', $decoded)) {
                return $decoded;
            }

            return $this->makeTipTapDocument($value);
        }

        if (is_array($value) && is_string($value['text'] ?? null)) {
            return $this->makeTipTapDocument($value['text']);
        }

        return $this->makeTipTapDocument('');
    }

    protected function makeTipTapDocument(string $text): array
    {
        $text = trim(strip_tags($text));

        if ($text === '') {
            return [
                'type' => 'doc',
                'content' => [
                    ['type' => 'paragraph'],
                ],
            ];
        }

        return [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $text,
                        ],
                    ],
                ],
            ],
        ];
    }
}
