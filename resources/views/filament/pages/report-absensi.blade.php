<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="pt-2">
                @livewire(\App\Filament\Widgets\AbsenceTopAlpaStudentsChart::class)
            </div>
            <div class="pt-2">
                @livewire(\App\Filament\Widgets\AbsenceTopLateStudentsChart::class)
            </div>
            <div class="pt-2">
                @livewire(\App\Filament\Widgets\AbsenceTopAlpaClassesChart::class)
            </div>
            <div class="pt-2">
                @livewire(\App\Filament\Widgets\AbsenceTopLateClassesChart::class)
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
