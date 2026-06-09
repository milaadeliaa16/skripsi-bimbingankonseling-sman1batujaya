<x-filament-panels::page>
    <div class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="pt-2 lk-card-section">
                @livewire(\App\Filament\Widgets\ViolationByStudentChart::class)
            </div>
            <div class="pt-2 lk-card-section">
                @livewire(\App\Filament\Widgets\ViolationByClassChart::class)
            </div>
        </div>

        <div class="pt-2 lk-card-section">
            @livewire(\App\Filament\Widgets\ViolationSummaryTable::class)
        </div>

        <div class="pt-2 lk-card-section">
            @livewire(\App\Filament\Widgets\AbsenceIssueSummaryTable::class)
        </div>
    </div>
</x-filament-panels::page>
