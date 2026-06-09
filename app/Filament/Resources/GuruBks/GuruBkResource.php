<?php

namespace App\Filament\Resources\GuruBks;

use App\Filament\Resources\GuruBks\Pages\CreateGuruBk;
use App\Filament\Resources\GuruBks\Pages\EditGuruBk;
use App\Filament\Resources\GuruBks\Pages\ListGuruBks;
use App\Filament\Resources\GuruBks\Schemas\GuruBkForm;
use App\Filament\Resources\GuruBks\Tables\GuruBksTable;
use App\Models\GuruBk;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class GuruBkResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Guru BK';

    protected static ?string $navigationLabel = 'Data Guru BK';

    protected static ?string $pluralModelLabel = 'Data Guru BK';

    protected static ?string $modelLabel = 'Guru BK';

    protected static ?int $navigationSort = 12;

    public static function form(Schema $schema): Schema
    {
        return GuruBkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuruBksTable::configure($table);
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
            'index' => ListGuruBks::route('/'),
            'create' => CreateGuruBk::route('/create'),
            'edit' => EditGuruBk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', User::ROLE_GURU_BK);
    }
}
