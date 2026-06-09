<x-filament-panels::page>
    <div wire:poll.15s="refreshConversation" class="space-y-4">
        <div
            class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900 curhat-messages-card">
            <div class="space-y-3 max-h-[28rem] overflow-y-auto pr-1">
                @forelse ($this->messages as $message)
                    @php
                        $isMine = (int) $message->user_id === (int) auth()->id();
                        $text = data_get($message->message, 'body', '');
                        $isAnonymousFromStudent =
                            $this->getRecord()->is_anonymous &&
                            auth()->user()?->type === \App\Models\User::ROLE_GURU_BK &&
                            $message->sender_type === \App\Models\User::ROLE_SISWA;
                        $senderName = $isAnonymousFromStudent
                            ? 'Siswa (Anonim)'
                            : $message->sender?->name ?? 'Pengguna';
                    @endphp

                    <div class="mb-3 flex {{ $isMine ? 'justify-end' : 'justify-start' }} last:mb-0">
                        <div
                            class="max-w-[80%] rounded-2xl px-4 py-3 {{ $isMine ? 'bg-primary-50 text-primary-950 ring-primary-200 dark:bg-primary-600 dark:text-white dark:ring-0' : 'bg-gray-100 text-gray-900 dark:bg-white/10 dark:text-white' }}">
                            <p class="text-xs opacity-80">
                                {{ $isMine ? 'Anda' : $senderName }}
                                -
                                {{ $message->created_at?->format('d M Y H:i') }}
                            </p>
                            <p class="mt-1 whitespace-pre-line text-sm">{{ $text }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-300">Belum ada pesan.</p>
                @endforelse
            </div>
        </div>

        @if ($this->canSendMessage())
            <form wire:submit="sendMessage"
                class="rounded-xl border border-gray-200 bg-white p-4 dark:border-white/10 dark:bg-gray-900">
                <label for="newMessage" class="mb-1 block text-sm font-medium">Tulis Balasan</label>
                <textarea id="newMessage" wire:model.defer="newMessage" rows="4"
                    class="w-full rounded-lg border-gray-300 p-3 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/15 dark:bg-white/5"
                    placeholder="Tulis pesan Anda..."></textarea>
                @error('newMessage')
                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                @enderror

                <div class="mt-3 flex justify-end">
                    <x-filament::button type="submit">
                        Kirim Pesan
                    </x-filament::button>
                </div>
            </form>
        @endif
    </div>
</x-filament-panels::page>
