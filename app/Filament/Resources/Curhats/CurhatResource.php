<?php

namespace App\Filament\Resources\Curhats;

use App\Filament\Resources\Curhats\Pages\CreateCurhat;
use App\Filament\Resources\Curhats\Pages\ListCurhats;
use App\Filament\Resources\Curhats\Pages\ViewCurhat;
use App\Filament\Resources\Curhats\Schemas\CurhatForm;
use App\Filament\Resources\Curhats\Tables\CurhatsTable;
use App\Models\Curhat;
use App\Models\CurhatMessage;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class CurhatResource extends Resource
{
    protected static ?string $model = Curhat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|UnitEnum|null $navigationGroup = 'Curhat & Konseling';

    protected static ?string $navigationLabel = 'Curhat';

    protected static ?string $pluralModelLabel = 'Curhat';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return CurhatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurhatsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurhats::route('/'),
            'create' => CreateCurhat::route('/create'),
            'view' => ViewCurhat::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->type, [User::ROLE_SISWA, User::ROLE_GURU_BK], true)) {
            return null;
        }

        $count = CurhatMessage::query()
            ->where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->whereHas('curhat', function (Builder $query) use ($user): void {
                $query->where(function (Builder $nestedQuery) use ($user): void {
                    $nestedQuery
                        ->where('student_id', $user->id)
                        ->orWhere('teacher_id', $user->id);
                });
            })
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

        $query = parent::getEloquentQuery()
            ->with(['student', 'teacher']);

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->type === User::ROLE_SISWA) {
            $query->where('student_id', $user->id);
        } elseif ($user->type === User::ROLE_GURU_BK) {
            $query->where('teacher_id', $user->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query->withCount([
            'messages as unread_messages_count' => function (Builder $messagesQuery) use ($user): void {
                $messagesQuery
                    ->where('user_id', '!=', $user->id)
                    ->where('is_read', false);
            },
        ]);
    }
}
