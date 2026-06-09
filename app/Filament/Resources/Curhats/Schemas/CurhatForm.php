<?php

namespace App\Filament\Resources\Curhats\Schemas;

use App\Models\User;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CurhatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('status')
                    ->default('aktif'),

                Hidden::make('student_id')
                    ->default(fn () => Auth::id()),

                Select::make('teacher_id')
                    ->label('Guru BK')
                    ->relationship(
                        name: 'teacher',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->guruBk(),
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('title')
                    ->label('Judul Curhat')
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_anonymous')
                    ->label('Sembunyikan nama saya dari guru BK')
                    ->default(false),

                Textarea::make('initial_message')
                    ->label('Isi Curhat Awal')
                    ->required()
                    ->rows(6)
                    ->maxLength(5000)
                    ->columnSpanFull(),
            ]);
    }
}



