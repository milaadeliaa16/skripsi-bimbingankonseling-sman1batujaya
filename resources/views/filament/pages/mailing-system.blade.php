<x-filament-panels::page>
    <form wire:submit="send" class="space-y-6">
        {{ $this->form }}

        <div>
            <x-filament::button type="submit" icon="heroicon-o-paper-airplane">
                Kirim Email
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
