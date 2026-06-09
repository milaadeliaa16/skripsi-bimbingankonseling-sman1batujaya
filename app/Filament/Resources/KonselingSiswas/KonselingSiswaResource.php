<?php

namespace App\Filament\Resources\KonselingSiswas;

use App\Filament\Resources\KonselingSiswas\Pages\CreateKonselingSiswa;
use App\Filament\Resources\KonselingSiswas\Pages\EditKonselingSiswa;
use App\Filament\Resources\KonselingSiswas\Pages\ListKonselingSiswas;
use App\Filament\Resources\KonselingSiswas\Pages\ViewKonselingSiswa;
use App\Filament\Resources\KonselingSiswas\Schemas\KonselingSiswaForm;
use App\Filament\Resources\KonselingSiswas\Tables\KonselingSiswasTable;
use App\Models\KonselingSiswa;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class KonselingSiswaResource extends Resource
{
    protected static ?string $model = KonselingSiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowUp;

    protected static ?string $navigationLabel = 'Konseling Siswa';

    protected static string|UnitEnum|null $navigationGroup = 'Curhat & Konseling';

    protected static ?string $pluralModelLabel = 'Konseling Siswa';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return KonselingSiswaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KonselingSiswasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKonselingSiswas::route('/'),
            'create' => CreateKonselingSiswa::route('/create'),
            'view' => ViewKonselingSiswa::route('/{record}'),
            'edit' => EditKonselingSiswa::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if (! $user || $user->type !== User::ROLE_GURU_BK) {
            return null;
        }

        $count = static::getEloquentQuery()
            ->where('is_read_by_counselor', false)
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
