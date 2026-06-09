<?php

namespace App\Filament\Resources\Curhats\Pages;

use App\Filament\Resources\Curhats\CurhatResource;
use App\Models\CurhatMessage;
use App\Models\User;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ViewCurhat extends ViewRecord
{
    protected static string $resource = CurhatResource::class;

    protected string $view = 'filament.resources.curhats.pages.view-curhat';

    public string $newMessage = '';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->markIncomingMessagesAsRead();
    }

    public function getTitle(): string
    {
        return $this->getRecord()->title;
    }

    public function getSubheading(): ?string
    {
        $record = $this->getRecord()->loadMissing(['student', 'teacher']);

        $counterpart = Auth::id() === $record->student_id
            ? $record->teacher?->name
            : ($record->is_anonymous ? 'Siswa (Anonim)' : $record->student?->name);

        return 'Percakapan dengan ' . $counterpart;
    }

    public function getMessagesProperty(): Collection
    {
        return $this->getRecord()
            ->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function refreshConversation(): void
    {
        $this->getRecord()->refresh();
        $this->markIncomingMessagesAsRead();
    }

    public function sendMessage(): void
    {
        $validated = $this->validate([
            'newMessage' => ['required', 'string', 'max:5000'],
        ]);

        CurhatMessage::create([
            'curhat_id' => $this->getRecord()->id,
            'user_id' => Auth::id(),
            'sender_type' => Auth::user()?->type,
            'is_read' => false,
            'message' => [
                'body' => trim($validated['newMessage']),
            ],
        ]);

        $this->getRecord()->update([
            'last_message_at' => now(),
            'status' => 'aktif',
        ]);

        $this->newMessage = '';
        $this->getRecord()->refresh();
    }

    public function canSendMessage(): bool
    {
        $user = Auth::user();
        $record = $this->getRecord();

        if (! $user || ! in_array($user->type, [User::ROLE_SISWA, User::ROLE_GURU_BK], true)) {
            return false;
        }

        return $record->student_id === $user->id || $record->teacher_id === $user->id;
    }

    protected function markIncomingMessagesAsRead(): void
    {
        if (! $this->canSendMessage()) {
            return;
        }

        CurhatMessage::query()
            ->where('curhat_id', $this->getRecord()->id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
