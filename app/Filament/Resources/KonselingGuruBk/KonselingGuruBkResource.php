<?php

namespace App\Filament\Resources\KonselingGuruBk;

use App\Filament\Resources\KonselingGuruBk\Pages\CreateKonselingGuruBk;
use App\Filament\Resources\KonselingGuruBk\Pages\EditKonselingGuruBk;
use App\Filament\Resources\KonselingGuruBk\Pages\ListKonselingGuruBk;
use App\Filament\Resources\KonselingGuruBk\Pages\ViewKonselingGuruBk;
use App\Filament\Resources\KonselingGuruBk\RelationManagers\StudentViolationHistoriesRelationManager;
use App\Filament\Resources\KonselingGuruBk\Schemas\KonselingGuruBkForm;
use App\Filament\Resources\KonselingGuruBk\Tables\KonselingGuruBkTable;
use App\Models\KonselingGuruBk;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class KonselingGuruBkResource extends Resource
{
    protected static ?string $model = KonselingGuruBk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static ?string $navigationLabel = 'Konseling Guru BK';

    protected static string|UnitEnum|null $navigationGroup = 'Curhat & Konseling';

    protected static ?string $pluralModelLabel = 'Konseling Guru BK';

    protected static ?int $navigationSort = 13;

    public static function form(Schema $schema): Schema
    {
        return KonselingGuruBkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KonselingGuruBkTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StudentViolationHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKonselingGuruBk::route('/'),
            'create' => CreateKonselingGuruBk::route('/create'),
            'view' => ViewKonselingGuruBk::route('/{record}'),
            'edit' => EditKonselingGuruBk::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if (! $user || $user->type !== User::ROLE_SISWA) {
            return null;
        }

        $count = static::getEloquentQuery()
            ->where('is_read_by_student', false)
            ->count();

        if ($count <= 0) {
            return null;
        }

        return (string) $count;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        $query = parent::getEloquentQuery();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->type === User::ROLE_SISWA) {
            return $query->where('student_id', $user->id);
        }

        if ($user->type === User::ROLE_GURU_BK) {
            return $query->where('counselor_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
