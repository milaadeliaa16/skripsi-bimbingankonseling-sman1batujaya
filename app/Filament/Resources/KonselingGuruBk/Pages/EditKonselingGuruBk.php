<?php

namespace App\Filament\Resources\KonselingGuruBk\Pages;

use App\Filament\Resources\KonselingGuruBk\KonselingGuruBkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKonselingGuruBk extends EditRecord
{
    protected static string $resource = KonselingGuruBkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach (['problem', 'summary', 'solution', 'notes'] as $field) {
            $data[$field] = $this->normalizeTipTapDocument($data[$field] ?? null);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()?->type === \App\Models\User::ROLE_GURU_BK) {
            $data['is_read_by_student'] = false;
            $data['read_at_by_student'] = null;
        }

        return $data;
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
